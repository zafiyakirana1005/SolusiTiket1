<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        match ($request->get('sort', 'latest')) {
            'harga' => $query->orderByDesc('price'),
            'stok'  => $query->orderBy('stock'),
            default => $query->latest('updated_at'),
        };

        $products     = $query->paginate(10)->withQueryString();
        $categories   = Category::orderBy('name')->get();
        $countAll      = Product::count();
        $countActive   = Product::where('status', 'active')->count();
        $countDraft    = Product::where('status', 'draft')->count();
        $countCritical = Product::get()->filter(fn ($p) => $p->totalStock() < 10 && $p->status !== 'archived')->count();
        $countArchived = Product::where('status', 'archived')->count();

        return view('admin.products.index', compact(
            'products', 'categories',
            'countAll', 'countActive', 'countDraft', 'countCritical', 'countArchived'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $product = Product::create([
            'category_id'    => $data['category_id'] ?? null,
            'name'           => $data['name'],
            'slug'           => Str::slug($data['name']) . '-' . Str::random(4),
            'subtitle'       => $data['subtitle'] ?? null,
            'description'    => $data['description'] ?? null,
            'sku'            => $data['sku'],
            'price'          => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'stock'          => $data['stock'] ?? 0,
            'weight_grams'   => $data['weight_grams'] ?? 500,
            'status'         => $data['status'],
            'image_path'     => $this->uploadImage($request, 'image'),
            'image_path_back'=> $this->uploadImage($request, 'image_back'),
        ]);

        $this->storeGalleryImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk baru berhasil disimpan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['variants', 'images']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request, $product->id);

        $imagesToRemove = $product->images()
            ->whereIn('id', $request->input('remove_images', []))
            ->get();

        foreach ($imagesToRemove as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $imagePath     = $product->image_path;
        $imagePathBack = $product->image_path_back;

        if ($request->hasFile('image')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $this->uploadImage($request, 'image');
        }

        if ($request->hasFile('image_back')) {
            if ($imagePathBack) Storage::disk('public')->delete($imagePathBack);
            $imagePathBack = $this->uploadImage($request, 'image_back');
        }

        $product->update([
            'category_id'    => $data['category_id'] ?? null,
            'name'           => $data['name'],
            'subtitle'       => $data['subtitle'] ?? null,
            'description'    => $data['description'] ?? null,
            'sku'            => $data['sku'],
            'price'          => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'stock'          => $data['stock'] ?? 0,
            'weight_grams'   => $data['weight_grams'] ?? 500,
            'status'         => $data['status'],
            'image_path'     => $imagePath,
            'image_path_back'=> $imagePathBack,
        ]);

        $this->storeGalleryImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path)      Storage::disk('public')->delete($product->image_path);
        if ($product->image_path_back) Storage::disk('public')->delete($product->image_path_back);
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function bulkStatus(Request $request)
    {
        Product::whereIn('id', $request->input('ids', []))->update(['status' => $request->input('status')]);
        return back()->with('success', 'Status produk terpilih berhasil diperbarui.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function uploadImage(Request $request, string $field): ?string
    {
        if (!$request->hasFile($field)) return null;
        return $request->file($field)->store('products', 'public');
    }

    private function storeGalleryImages(Request $request, Product $product): void
    {
        $nextOrder = (int) $product->images()->max('sort_order') + 1;

        foreach ($request->file('gallery_images', []) as $index => $image) {
            $product->images()->create([
                'image_path' => $image->store('products/gallery', 'public'),
                'sort_order' => $nextOrder + $index,
            ]);
        }
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'category_id'  => 'nullable|exists:categories,id',
            'name'         => 'required|string|max:150',
            'subtitle'     => 'nullable|string|max:150',
            'description'  => 'nullable|string',
            'sku'          => 'required|string|max:50|unique:products,sku' . ($ignoreId ? ",$ignoreId" : ''),
            'variant_sku'  => 'sometimes|array',
            'variant_sku.*' => 'nullable|string|max:50',
            'price'        => 'required|integer|min:0',
            'compare_at_price' => 'nullable|integer|gt:price',
            'stock'        => 'nullable|integer|min:0',
            'weight_grams' => 'nullable|integer|min:0',
            'status'       => 'required|in:active,draft,out_of_stock,archived',
            'image'        => 'nullable|image|max:2048',
            'image_back'   => 'nullable|image|max:2048',
            'gallery_images' => 'sometimes|array|max:8',
            'gallery_images.*' => 'image|max:4096',
            'remove_images' => 'sometimes|array',
            'remove_images.*' => 'integer',
        ], [
            'sku.unique' => 'SKU sudah ada. Gunakan SKU lain.',
        ]);

        $validator->after(function ($validator) use ($request, $ignoreId): void {
            $mainSku = trim((string) $request->input('sku'));
            $existingVariantQuery = ProductVariant::query()->where('sku', $mainSku);

            if ($ignoreId) {
                $existingVariantQuery->where('product_id', '!=', $ignoreId);
            }

            if ($mainSku !== '' && $existingVariantQuery->exists()) {
                $validator->errors()->add('sku', 'SKU sudah ada. Gunakan SKU lain.');
            }

            $seenVariantSkus = [];

            foreach ((array) $request->input('variant_sku', []) as $variantSku) {
                $variantSku = trim((string) $variantSku);
                if ($variantSku === '') {
                    continue;
                }

                $normalizedSku = strtoupper($variantSku);
                if ($normalizedSku === strtoupper($mainSku) || isset($seenVariantSkus[$normalizedSku])) {
                    $validator->errors()->add('variant_sku', 'SKU sudah ada. Gunakan SKU lain.');
                    continue;
                }
                $seenVariantSkus[$normalizedSku] = true;

                $existingProductQuery = Product::query()->where('sku', $variantSku);
                $existingVariantQuery = ProductVariant::query()->where('sku', $variantSku);

                if ($ignoreId) {
                    $existingProductQuery->where('id', '!=', $ignoreId);
                    $existingVariantQuery->where('product_id', '!=', $ignoreId);
                }

                if ($existingProductQuery->exists() || $existingVariantQuery->exists()) {
                    $validator->errors()->add('variant_sku', 'SKU sudah ada. Gunakan SKU lain.');
                }
            }
        });

        return $validator->validate();
    }

    private function syncVariants(Request $request, Product $product): void
    {
        if (!$request->boolean('variants_present') && !$request->has('variant_name')) return;

        $names  = $request->input('variant_name', []);
        $skus   = $request->input('variant_sku', []);
        $stocks = $request->input('variant_stock', []);
        $shelves = $request->input('variant_shelf', []);

        $product->variants()->delete();

        foreach ($names as $i => $name) {
            if (empty($name)) continue;
            ProductVariant::create([
                'product_id'     => $product->id,
                'name'           => $name,
                'sku'            => $skus[$i] ?? ($product->sku . '-' . Str::upper(Str::random(3))),
                'stock'          => $stocks[$i] ?? 0,
                'shelf_location' => $shelves[$i] ?? null,
            ]);
        }
    }
}
