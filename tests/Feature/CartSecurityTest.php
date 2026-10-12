<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_items_from_another_session_cannot_be_changed_or_removed(): void
    {
        $product = Product::create([
            'name' => 'Produk Aman',
            'sku' => 'AMAN-001',
            'price' => 10000,
            'stock' => 5,
            'status' => 'active',
        ]);
        $item = CartItem::create([
            'session_id' => 'another-session',
            'product_id' => $product->id,
            'unit_price' => 10000,
            'qty' => 1,
        ]);

        $this->patch(route('cart.update', $item), ['qty' => 2])->assertNotFound();
        $this->delete(route('cart.remove', $item))->assertNotFound();

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'session_id' => 'another-session',
            'qty' => 1,
        ]);
    }

    public function test_cart_rejects_a_variant_that_belongs_to_another_product(): void
    {
        $firstProduct = Product::create([
            'name' => 'Produk Pertama',
            'sku' => 'AMAN-002',
            'price' => 10000,
            'stock' => 0,
            'status' => 'active',
        ]);
        $secondProduct = Product::create([
            'name' => 'Produk Kedua',
            'sku' => 'AMAN-003',
            'price' => 20000,
            'stock' => 0,
            'status' => 'active',
        ]);
        $variant = ProductVariant::create([
            'product_id' => $secondProduct->id,
            'name' => 'Ukuran M',
            'sku' => 'AMAN-003-M',
            'stock' => 4,
        ]);

        $this->from(route('product-details', $firstProduct->slug))
            ->post(route('cart.add'), [
                'product_id' => $firstProduct->id,
                'variant_id' => $variant->id,
                'qty' => 1,
            ])
            ->assertSessionHasErrors('variant_id');

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_cart_rejects_quantities_above_available_stock(): void
    {
        $product = Product::create([
            'name' => 'Stok Terbatas',
            'sku' => 'AMAN-004',
            'price' => 10000,
            'stock' => 1,
            'status' => 'active',
        ]);

        $this->from(route('product-details', $product->slug))
            ->post(route('cart.add'), ['product_id' => $product->id, 'qty' => 2])
            ->assertSessionHasErrors('qty');

        $this->assertDatabaseCount('cart_items', 0);
    }
}