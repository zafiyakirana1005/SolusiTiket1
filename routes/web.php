<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ─── Halaman Publik ──────────────────────────────────────────────
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/new-arrivals', [ShopController::class, 'newArrivals'])->name('new-arrivals');
Route::get('/promo', [ShopController::class, 'promos'])->name('promo');
Route::get('/product-details/{product:slug}', [ShopController::class, 'show'])->name('product-details');

// ─── Keranjang ───────────────────────────────────────────────────
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo.apply');
Route::delete('/cart/promo/remove', [CartController::class, 'removePromo'])->name('cart.promo.remove');

// ─── Checkout ────────────────────────────────────────────────────
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order-success/{order_number}', function ($order_number) {
    return view('frontend.storefront-order-success', ['orderNumber' => $order_number]);
})->name('order.success');

// ─── Halaman statis lainnya ──────────────────────────────────────
Route::get('/login', [CustomerAuthController::class, 'login'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'storeLogin'])->middleware('throttle:5,1')->name('login.store');
Route::get('/register', [CustomerAuthController::class, 'register'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'storeRegistration'])->middleware('throttle:5,1')->name('register.store');
Route::get('/wishlist', fn () => view('frontend.storefront-info', [
    'eyebrow' => 'Wishlist',
    'pageTitle' => 'Wishlist belum tersedia',
    'pageDescription' => 'Fitur simpan produk belum aktif. Anda tetap dapat menjelajahi koleksi dan membuka detail produk.',
]))->name('wishlist');
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/account', [CustomerAuthController::class, 'account'])->name('account');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
});
Route::get('/contact', fn () => view('frontend.storefront-info', [
    'eyebrow' => 'Bantuan',
    'pageTitle' => 'Informasi kontak sedang diperbarui',
    'pageDescription' => 'Untuk pertanyaan tentang pesanan, siapkan nomor pesanan saat menghubungi kanal resmi The Lodge Maribaya.',
]))->name('contact');
Route::get('/tumbler', fn () => redirect()->route('shop', ['q' => 'tumbler']))->name('tumbler');


// ─── Admin ───────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('products/bulk-status', [ProductController::class, 'bulkStatus'])->name('products.bulk-status');

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/notes', [OrderController::class, 'storeNote'])->name('orders.notes.store');

    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    Route::resource('promos', PromoController::class)->except(['show']);
    Route::patch('promos/{promo}/toggle-status', [PromoController::class, 'toggleStatus'])->name('promos.toggle-status');
});
