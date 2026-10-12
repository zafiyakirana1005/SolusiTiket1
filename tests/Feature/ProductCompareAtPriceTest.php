<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCompareAtPriceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_admin_can_save_a_compare_at_price_higher_than_the_sale_price(): void
    {
        $this->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('Harga Coret (Rp)')
            ->assertSee('name="compare_at_price"', false);

        $this->post(route('admin.products.store'), [
            'name' => 'Produk Diskon',
            'sku' => 'DISKON-001',
            'price' => 50000,
            'compare_at_price' => 65000,
            'status' => 'active',
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'sku' => 'DISKON-001',
            'price' => 50000,
            'compare_at_price' => 65000,
        ]);
    }

    public function test_compare_at_price_must_be_higher_than_the_sale_price(): void
    {
        $this->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                'name' => 'Produk Diskon',
                'sku' => 'DISKON-002',
                'price' => 50000,
                'compare_at_price' => 50000,
                'status' => 'active',
            ])
            ->assertRedirect(route('admin.products.create'))
            ->assertSessionHasErrors('compare_at_price');
    }

    public function test_duplicate_product_sku_is_rejected_with_a_clear_message(): void
    {
        Product::create([
            'name' => 'Produk Sebelumnya',
            'sku' => 'SKU-DUPLIKAT-001',
            'price' => 25000,
            'status' => 'active',
        ]);

        $this->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                'name' => 'Produk Duplikat',
                'sku' => 'SKU-DUPLIKAT-001',
                'price' => 30000,
                'status' => 'active',
            ])
            ->assertRedirect(route('admin.products.create'))
            ->assertSessionHasErrors(['sku' => 'SKU sudah ada. Gunakan SKU lain.']);

        $this->assertDatabaseCount('products', 1);
    }

    public function test_duplicate_variant_sku_is_rejected_with_a_clear_message(): void
    {
        $existingProduct = Product::create([
            'name' => 'Produk Varian Sebelumnya',
            'sku' => 'SKU-PARENT-001',
            'price' => 25000,
            'status' => 'active',
        ]);
        $existingProduct->variants()->create([
            'name' => 'Ukuran M',
            'sku' => 'SKU-VARIAN-001',
            'stock' => 3,
        ]);

        $this->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                'name' => 'Produk Varian Duplikat',
                'sku' => 'SKU-PARENT-002',
                'price' => 30000,
                'status' => 'active',
                'variant_name' => ['Ukuran L'],
                'variant_sku' => ['SKU-VARIAN-001'],
                'variant_stock' => [1],
            ])
            ->assertRedirect(route('admin.products.create'))
            ->assertSessionHasErrors(['variant_sku' => 'SKU sudah ada. Gunakan SKU lain.']);

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('product_variants', 1);
    }

    public function test_updating_product_with_all_variants_removed_deletes_them(): void
    {
        $product = Product::create([
            'name' => 'Produk Dengan Varian',
            'sku' => 'SKU-HAPUS-VARIAN-001',
            'price' => 30000,
            'status' => 'active',
        ]);
        $product->variants()->create([
            'name' => 'Ukuran M',
            'sku' => 'SKU-HAPUS-VARIAN-001-M',
            'stock' => 2,
        ]);

        $this->put(route('admin.products.update', $product), [
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'status' => 'active',
            'variants_present' => '1',
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseCount('product_variants', 0);
    }

    public function test_compare_at_price_is_shown_on_storefront_pages(): void
    {
        $category = Category::create(['name' => 'Aksesori', 'slug' => 'aksesori']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Diskon',
            'sku' => 'DISKON-003',
            'price' => 50000,
            'compare_at_price' => 65000,
            'stock' => 2,
            'status' => 'active',
        ]);

        foreach ([route('home'), route('shop'), route('product-details', $product->slug)] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Rp 50.000')
                ->assertSee('<del>Rp 65.000</del>', false);
        }

        $this->get(route('home'))->assertSee('store-product__sale-badge', false)->assertSee('Sale');
        $this->get(route('shop'))->assertSee('store-product__sale-badge', false)->assertSee('Sale');
    }
}