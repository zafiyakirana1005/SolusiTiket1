<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    private function sessionId(): string
    {
        return CartController::cartSessionId();
    }

    public function index()
    {
        $items = CartItem::with('product', 'variant')
            ->where('session_id', $this->sessionId())
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $subtotal = $items->sum(fn ($i) => $i->unit_price * $i->qty);
        $discount = 0;
        $promoCode = session('promo_code');
        $activePromo = null;

        if ($promoCode) {
            $promo = Promo::where('code', $promoCode)->first();
            if ($promo) {
                $result = $promo->calculateDiscount($subtotal);
                $discount = $result['discount'];
                $activePromo = $promo;
            }
        }

        $shippingCost = 15000; // default — bisa dibuat dinamis berdasarkan kota
        $total = $subtotal - $discount + $shippingCost;
        $customer = Auth::user()?->role === 'customer'
            ? Customer::where('user_id', Auth::id())->first()
            : null;

        return view('frontend.storefront-checkout', compact('items', 'subtotal', 'discount', 'total', 'shippingCost', 'promoCode', 'activePromo', 'customer'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:300',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'courier' => 'required|in:JNE,J&T,SiCepat,Anteraja,Pos Indonesia',
            'payment_method' => 'required|in:Transfer BCA,Transfer Mandiri,QRIS,GoPay,OVO,Dana,COD',
            'buyer_note' => 'nullable|string|max:500',
        ]);

        $orderNumber = DB::transaction(function () use ($data, $request): ?string {
            $items = CartItem::query()
                ->where('session_id', $this->sessionId())
                ->orderBy('product_id')
                ->orderBy('product_variant_id')
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                return null;
            }

            $preparedItems = [];
            $reservations = [];
            $priceChanged = false;

            foreach ($items as $item) {
                $product = Product::query()->lockForUpdate()->find($item->product_id);
                if (! $product || $product->status !== 'active') {
                    throw ValidationException::withMessages(['cart' => 'Ada produk di keranjang yang sudah tidak tersedia.']);
                }

                $variant = null;
                if ($item->product_variant_id) {
                    $variant = ProductVariant::query()
                        ->where('product_id', $product->id)
                        ->lockForUpdate()
                        ->find($item->product_variant_id);

                    if (! $variant) {
                        throw ValidationException::withMessages(['cart' => 'Varian di keranjang sudah tidak tersedia.']);
                    }
                } elseif ($product->variants()->exists()) {
                    throw ValidationException::withMessages(['cart' => 'Pilih varian produk sebelum checkout.']);
                }

                $quantity = (int) $item->qty;
                $price = $variant?->price_override ?? $product->price;
                if ($item->unit_price !== $price || $item->variant_name !== $variant?->name) {
                    $item->update(['unit_price' => $price, 'variant_name' => $variant?->name]);
                    $priceChanged = true;
                }

                $inventory = $variant ?? $product;
                $inventoryKey = $variant ? 'variant-'.$variant->id : 'product-'.$product->id;
                $reservations[$inventoryKey] ??= ['record' => $inventory, 'quantity' => 0];
                $reservations[$inventoryKey]['quantity'] += $quantity;
                $preparedItems[] = compact('item', 'product', 'variant', 'quantity', 'price');
            }

            if ($priceChanged) {
                return 'price_changed';
            }

            foreach ($reservations as $reservation) {
                if ($reservation['quantity'] > $reservation['record']->stock) {
                    throw ValidationException::withMessages(['cart' => 'Stok berubah dan tidak mencukupi. Periksa kembali keranjang.']);
                }
            }

            $subtotal = collect($preparedItems)->sum(fn ($prepared) => $prepared['price'] * $prepared['quantity']);
            $discount = 0;
            $promoUsed = null;
            if ($promoCode = session('promo_code')) {
                $promo = Promo::query()->where('code', $promoCode)->lockForUpdate()->first();
                if ($promo) {
                    $result = $promo->calculateDiscount($subtotal);
                    if ($result['eligible']) {
                        $discount = $result['discount'];
                        $promoUsed = $promo;
                    }
                }
            }

            $shippingCost = 15000;
            $total = $subtotal - $discount + $shippingCost;
            $customer = Auth::user()?->role === 'customer'
                ? Customer::firstOrNew(['user_id' => Auth::id()])
                : Customer::firstOrNew(['phone' => $data['phone'], 'user_id' => null]);
            $customer->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'province' => $data['province'],
            ])->save();

            $order = Order::create([
                'order_number' => 'ORD-'.date('Y').'-'.strtoupper(Str::random(6)),
                'trx_id' => 'TRX-'.strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'fulfillment_status' => 'pending_payment',
                'inventory_reserved' => true,
                'courier' => $data['courier'],
                'shipping_address' => $data['address'],
                'shipping_city' => $data['city'],
                'shipping_province' => $data['province'],
                'buyer_note' => $data['buyer_note'] ?? null,
                'source' => 'Website Store',
                'customer_ip' => $request->ip(),
            ]);

            foreach ($reservations as $reservation) {
                $reservation['record']->decrement('stock', $reservation['quantity']);
            }

            foreach ($preparedItems as $prepared) {
                $item = $prepared['item'];
                $product = $prepared['product'];
                $variant = $prepared['variant'];
                $quantity = $prepared['quantity'];
                $price = $prepared['price'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant?->name,
                    'sku' => $variant?->sku ?? $product->sku,
                    'shelf_location' => $variant?->shelf_location,
                    'unit_price' => $price,
                    'qty' => $quantity,
                    'subtotal' => $price * $quantity,
                    'weight_grams' => ($product->weight_grams ?? 500) * $quantity,
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'actor_name' => $customer->name,
                'actor_type' => 'customer',
                'description' => 'Pesanan baru dibuat via checkout website.',
            ]);

            $promoUsed?->increment('used_count');
            CartItem::where('session_id', $this->sessionId())->delete();
            session()->forget('promo_code');

            return $order->order_number;
        });

        if ($orderNumber === null) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong.');
        }

        if ($orderNumber === 'price_changed') {
            return redirect()->route('cart')->with('error', 'Harga produk berubah. Periksa kembali keranjang sebelum checkout.');
        }

        return redirect()->route('order.success', $orderNumber);
    }
}
