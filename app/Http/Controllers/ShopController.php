<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promo;

class ShopController extends Controller
{
    /** Homepage — ambil produk terbaru & produk "sale" */
    public function home()
    {
        $newProducts = Product::with('category')
            ->where('status', 'active')
            ->latest()
            ->take(9)
            ->get();

        $saleProducts = Product::with('category')
            ->where('status', 'active')
            ->inRandomOrder()
            ->take(6)
            ->get();

        $activePromos = Promo::where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->take(3)
            ->get();

        $popupPromos = Promo::where('popup_enabled', true)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where(function ($query) {
                $query->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            })
            ->latest()
            ->get();

        return view('frontend.storefront-home', compact('newProducts', 'saleProducts', 'activePromos', 'popupPromos'));
    }

    /** Halaman katalog/shop */
    public function index()
    {
        $query = Product::with('category')->where('status', 'active');

        if ($category = request('category')) {
            if (is_numeric($category)) {
                $query->where('category_id', $category);
            } else {
                $query->whereHas('category', function ($categoryQuery) use ($category) {
                    $categoryQuery->where('slug', $category)
                        ->orWhere('name', 'like', '%' . $category . '%');
                });
            }
        }

        if ($searchTerm = trim((string) request('q'))) {
            $normalizedTerm = strtolower($searchTerm);
            $keywordMap = [
                'baju' => ['baju', 'apparel', 'pakaian', 'hoodie', 'jaket', 'shirt', 'scarf'],
                'apparel' => ['apparel', 'baju', 'pakaian', 'hoodie', 'jaket', 'shirt', 'scarf'],
                'tumbler' => ['tumbler', 'botol', 'mug', 'drinkware', 'minuman', 'thermos'],
                'drinkware' => ['drinkware', 'tumbler', 'botol', 'mug', 'minuman', 'thermos'],
                'aksesoris' => ['aksesoris', 'aksesori', 'tas', 'bag', 'souvenir', 'scarf', 'pin'],
            ];

            $keywords = $keywordMap[$normalizedTerm] ?? [$searchTerm];

            $query->where(function ($queryBuilder) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $queryBuilder->orWhere('name', 'like', '%' . $keyword . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($keyword) {
                            $categoryQuery->where('name', 'like', '%' . $keyword . '%')
                                ->orWhere('slug', 'like', '%' . $keyword . '%');
                        });
                }
            });
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('frontend.storefront-catalog', compact('products', 'categories'));
    }

    /** Halaman new arrivals (produk 30 hari terakhir) */
    public function newArrivals()
    {
        $products = Product::with('category')
            ->where('status', 'active')
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->paginate(12);

        $categories = Category::orderBy('name')->get();

        $pageTitle = 'Produk terbaru';

        return view('frontend.storefront-catalog', compact('products', 'categories', 'pageTitle'));
    }

    /** Detail 1 produk */
    public function show(Product $product)
    {
        abort_unless($product->status === 'active', 404);
        $product->load('variants', 'category');

        $related = Product::where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('frontend.storefront-product', compact('product', 'related'));
    }

    /** Halaman promo publik */
    public function promos()
    {
        $promos = Promo::where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where(function ($q) {
                $q->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            })
            ->latest()
            ->paginate(9);

        return view('frontend.storefront-promos', compact('promos'));
    }
}
