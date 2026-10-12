<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The storefront homepage renders its primary message.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Kenangan dari alam, dibawa pulang.')
            ->assertSee('data-home-slider', false)
            ->assertSee('Tampilkan slide 3')
            ->assertSee('Slide berikutnya')
            ->assertSee('data-mobile-menu-toggle', false)
            ->assertSee('aria-controls="storefront-menu"', false)
            ->assertSee('name="q"', false)
            ->assertSee('Cari produk')
            ->assertSee('Keranjang');
    }

    public function test_the_shop_catalog_renders_when_no_products_are_available(): void
    {
        $response = $this->get('/shop');

        $response->assertOk()
            ->assertSee('Belum ada produk yang tersedia saat ini.');
    }

    public function test_new_arrivals_uses_the_storefront_catalog(): void
    {
        $this->get('/new-arrivals')
            ->assertOk()
            ->assertSee('Produk terbaru')
            ->assertDontSee('Outstock');
    }

    public function test_promotions_show_a_clean_empty_state(): void
    {
        $this->get('/promo')
            ->assertOk()
            ->assertSee('Belum ada promo aktif.')
            ->assertDontSee('Outstock');
    }

    public function test_static_storefront_pages_use_the_branded_status_page(): void
    {
        foreach ([
            '/wishlist' => 'Wishlist belum tersedia',
            '/contact' => 'Informasi kontak sedang diperbarui',
        ] as $path => $title) {
            $this->get($path)
                ->assertOk()
                ->assertSee($title)
                ->assertDontSee('Outstock');
        }
    }

    public function test_tumbler_route_redirects_to_the_catalog_search(): void
    {
        $this->get('/tumbler')
            ->assertRedirect(route('shop', ['q' => 'tumbler']));
    }

    public function test_the_cart_shows_an_empty_state(): void
    {
        $response = $this->get('/cart');

        $response->assertOk()
            ->assertSee('Keranjangmu masih kosong');
    }

    public function test_checkout_redirects_when_the_cart_is_empty(): void
    {
        $this->get('/checkout')
            ->assertRedirect(route('cart'));
    }

    public function test_order_confirmation_shows_the_order_number(): void
    {
        $this->get(route('order.success', 'ORD-2026-ABC123'))
            ->assertOk()
            ->assertSee('Pesananmu berhasil dibuat.')
            ->assertSee('ORD-2026-ABC123');
    }

    public function test_a_product_can_be_added_to_the_cart_and_checked_out(): void
    {
        $category = Category::create(['name' => 'Apparel', 'slug' => 'apparel']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kaos Lodge',
            'sku' => 'KAOS-001',
            'price' => 50000,
            'stock' => 3,
            'image_path' => 'products/kaos-depan.jpg',
            'image_path_back' => 'products/kaos-belakang.jpg',
            'status' => 'active',
        ]);

        $this->get(route('product-details', $product->slug))
            ->assertOk()
            ->assertSee('Kaos Lodge')
            ->assertSee('Tambah ke keranjang')
            ->assertSee('Pilihan foto produk')
            ->assertSee('Pilih Foto depan')
            ->assertSee('Pilih Foto belakang')
            ->assertSee('Perbesar Foto depan');

        $this->post(route('cart.add'), ['product_id' => $product->id, 'qty' => 2])
            ->assertRedirect();

        $this->withCookie(config('session.cookie'), session()->getId());

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Kaos Lodge')
            ->assertSee('Rp 100.000');

        $this->get(route('checkout'))
            ->assertOk()
            ->assertSee('Detail pengiriman')
            ->assertSee('Kaos Lodge')
            ->assertSee('buyer-name', false);
    }
}
