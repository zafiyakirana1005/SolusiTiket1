<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    private function sessionId(): string
    {
        return self::cartSessionId();
    }

    public static function cartSessionId(): string
    {
        $user = auth()->user();

        return $user?->role === 'customer'
            ? 'customer:' . $user->id
            : session()->getId();
    }

    public static function mergeGuestCart(string $guestSessionId, int $customerId): void
    {
        $customerSessionId = 'customer:' . $customerId;

        if ($guestSessionId === $customerSessionId) {
            return;
        }

        DB::transaction(function () use ($guestSessionId, $customerSessionId): void {
            $guestItems = CartItem::query()
                ->where('session_id', $guestSessionId)
                ->lockForUpdate()
                ->get();

            foreach ($guestItems as $guestItem) {
                $existingQuery = CartItem::query()
                    ->where('session_id', $customerSessionId)
                    ->where('product_id', $guestItem->product_id);

                if ($guestItem->product_variant_id === null) {
                    $existingQuery->whereNull('product_variant_id');
                } else {
                    $existingQuery->where('product_variant_id', $guestItem->product_variant_id);
                }

                $existingItem = $existingQuery->lockForUpdate()->first();

                if ($existingItem) {
                    $existingItem->increment('qty', $guestItem->qty);
                    $guestItem->delete();
                    continue;
                }

                $guestItem->update(['session_id' => $customerSessionId]);
            }
        });
    }

    /** @return Collection<int, CartItem> */
    private function cartItems(): Collection
    {
        return CartItem::with('product', 'variant')
            ->where('session_id', $this->sessionId())
            ->get();
    }

    public function index()
    {
        $items = $this->cartItems();
        $subtotal = $items->sum(fn ($i) => $i->subtotal());

        $discount = 0;
        $promoCode = session('promo_code');
        $promoMessage = null;
        $activePromo = null;

        if ($promoCode) {
            $promo = Promo::where('code', $promoCode)->first();
            if ($promo) {
                $result = $promo->calculateDiscount($subtotal);
                $discount = $result['discount'];
                $promoMessage = $result['eligible'] ? null : $result['reason'];
                $activePromo = $promo;
            }
        }

        $total = $subtotal - $discount;

        return view('frontend.storefront-cart', compact('items', 'subtotal', 'discount', 'total', 'promoCode', 'promoMessage', 'activePromo'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        DB::transaction(function () use ($data): void {
            $product = Product::query()->lockForUpdate()->findOrFail($data['product_id']);
            $variant = $this->resolveVariant($product, isset($data['variant_id']) ? (int) $data['variant_id'] : null);
            $quantity = (int) ($data['qty'] ?? 1);

            $existing = CartItem::query()
                ->where('session_id', $this->sessionId())
                ->where('product_id', $product->id)
                ->where('product_variant_id', $variant?->id)
                ->lockForUpdate()
                ->first();

            $newQuantity = ($existing?->qty ?? 0) + $quantity;
            $this->ensurePurchasable($product, $variant, $newQuantity);
            $price = $variant?->price_override ?? $product->price;

            if ($existing) {
                $existing->update(['qty' => $newQuantity, 'unit_price' => $price]);
            } else {
                CartItem::create([
                    'session_id' => $this->sessionId(),
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'variant_name' => $variant?->name,
                    'unit_price' => $price,
                    'qty' => $newQuantity,
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => CartItem::where('session_id', $this->sessionId())->sum('qty'),
                'message' => 'Produk ditambahkan ke keranjang.',
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $data = $request->validate(['qty' => 'required|integer|min:1']);

        DB::transaction(function () use ($cartItem, $data): void {
            $item = CartItem::query()
                ->where('session_id', $this->sessionId())
                ->lockForUpdate()
                ->findOrFail($cartItem->id);
            $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);
            $variant = $this->resolveVariant($product, $item->product_variant_id);
            $quantity = (int) $data['qty'];

            $this->ensurePurchasable($product, $variant, $quantity);
            $item->update([
                'qty' => $quantity,
                'unit_price' => $variant?->price_override ?? $product->price,
            ]);
        });

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(CartItem $cartItem)
    {
        CartItem::query()
            ->where('session_id', $this->sessionId())
            ->findOrFail($cartItem->id)
            ->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $code = strtoupper(trim($request->coupon_code));
        $promo = Promo::where('code', $code)->first();

        if (!$promo) {
            return back()->with('promo_error', 'Kode promo tidak ditemukan.');
        }

        $items = $this->cartItems();
        $subtotal = $items->sum(fn ($i) => $i->subtotal());
        $result = $promo->calculateDiscount($subtotal);

        if (!$result['eligible']) {
            return back()->with('promo_error', $result['reason']);
        }

        session(['promo_code' => $code]);

        return back()->with('promo_success', 'Kode promo berhasil diterapkan! Hemat Rp ' . number_format($result['discount'], 0, ',', '.'));
    }

    public function removePromo()
    {
        session()->forget('promo_code');

        return back()->with('success', 'Kode promo dihapus.');
    }

    /** Jumlah item di keranjang, untuk badge di header */
    public static function count(): int
    {
        return CartItem::where('session_id', self::cartSessionId())->sum('qty');
    }

    private function resolveVariant(Product $product, ?int $variantId): ?ProductVariant
    {
        if ($variantId === null) {
            if ($product->variants()->exists()) {
                throw ValidationException::withMessages(['variant_id' => 'Pilih varian produk terlebih dahulu.']);
            }

            return null;
        }

        $variant = ProductVariant::query()
            ->where('product_id', $product->id)
            ->lockForUpdate()
            ->find($variantId);

        if (! $variant) {
            throw ValidationException::withMessages(['variant_id' => 'Varian tidak sesuai dengan produk.']);
        }

        return $variant;
    }

    private function ensurePurchasable(Product $product, ?ProductVariant $variant, int $quantity): void
    {
        if ($product->status !== 'active') {
            throw ValidationException::withMessages(['product_id' => 'Produk ini tidak tersedia.']);
        }

        $availableStock = $variant?->stock ?? $product->stock;

        if ($quantity > $availableStock) {
            throw ValidationException::withMessages(['qty' => 'Jumlah melebihi stok yang tersedia.']);
        }
    }
}
