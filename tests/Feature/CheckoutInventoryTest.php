<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_reserves_stock_and_cancellation_releases_it_once(): void
    {
        $product = $this->product(stock: 5);
        $order = $this->placeOrder($product, quantity: 2);

        $this->assertSame(3, $product->fresh()->stock);
        $this->assertTrue($order->fresh()->inventory_reserved);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->post(route('admin.orders.update-status', $order), ['fulfillment_status' => 'cancelled'])
            ->assertRedirect();

        $this->assertSame(5, $product->fresh()->stock);
        $this->assertFalse($order->fresh()->inventory_reserved);

        $this->post(route('admin.orders.update-status', $order), ['fulfillment_status' => 'cancelled'])
            ->assertRedirect();
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_marking_an_order_paid_updates_its_payment_status(): void
    {
        $product = $this->product(stock: 5);
        $order = $this->placeOrder($product, quantity: 1);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.orders.update-status', $order), ['fulfillment_status' => 'paid'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'fulfillment_status' => 'paid',
            'payment_status' => 'paid',
            'inventory_reserved' => true,
        ]);
    }

    public function test_checkout_refreshes_changed_cart_prices_before_ordering(): void
    {
        $product = $this->product(stock: 5);
        $sessionId = $this->addToCart($product, quantity: 1);
        $product->update(['price' => 12000]);

        $this->withCookie(config('session.cookie'), $sessionId)
            ->post(route('checkout.store'), $this->checkoutData())
            ->assertRedirect(route('cart'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('cart_items', [
            'session_id' => $sessionId,
            'unit_price' => 12000,
        ]);
        $this->assertSame(5, $product->fresh()->stock);
    }

    private function product(int $stock): Product
    {
        return Product::create([
            'name' => 'Produk Uji',
            'sku' => 'UJI-'.fake()->unique()->numerify('####'),
            'price' => 10000,
            'stock' => $stock,
            'weight_grams' => 500,
            'status' => 'active',
        ]);
    }

    private function addToCart(Product $product, int $quantity): string
    {
        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'qty' => $quantity,
        ])->assertRedirect();

        return session()->getId();
    }

    private function placeOrder(Product $product, int $quantity): Order
    {
        $sessionId = $this->addToCart($product, $quantity);

        $this->withCookie(config('session.cookie'), $sessionId)
            ->post(route('checkout.store'), $this->checkoutData())
            ->assertRedirect();

        return Order::query()->latest('id')->firstOrFail();
    }

    private function checkoutData(): array
    {
        return [
            'name' => 'Pembeli Uji',
            'email' => 'pembeli@example.com',
            'phone' => '081234567890',
            'address' => 'Jalan Uji 1',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'courier' => 'JNE',
            'payment_method' => 'Transfer BCA',
        ];
    }
}