<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Promo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartPromoTest extends TestCase
{
    use RefreshDatabase;

    public function test_removing_a_promo_clears_its_discount_from_the_cart(): void
    {
        $product = $this->createProduct();
        $promo = $this->createPromo('REMOVE10');
        $sessionId = $this->createCartItem($product, $promo->code);
        $this->withCookie(config('session.cookie'), $sessionId);

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('REMOVE10')
            ->assertSee('- Rp 10.000');

        $this->delete(route('cart.promo.remove'))
            ->assertRedirect();

        $this->get(route('cart'))
            ->assertOk()
            ->assertDontSee('REMOVE10')
            ->assertDontSee('- Rp 10.000')
            ->assertSee('Rp 100.000');

        $this->assertFalse(session()->has('promo_code'));
    }

    public function test_applying_an_unknown_code_keeps_the_previous_promo_and_shows_an_error(): void
    {
        $product = $this->createProduct();
        $promo = $this->createPromo('VALID10');
        $sessionId = $this->createCartItem($product, $promo->code);
        $this->withCookie(config('session.cookie'), $sessionId);

        $this->from(route('cart'))
            ->post(route('cart.promo.apply'), ['coupon_code' => 'NOTFOUND'])
            ->assertRedirect(route('cart'))
            ->assertSessionHas('promo_error', 'Kode promo tidak ditemukan.');

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Kode promo tidak ditemukan.')
            ->assertSee('VALID10')
            ->assertSee('- Rp 10.000')
            ->assertSee('Rp 90.000');

        $this->assertSame('VALID10', session('promo_code'));
    }

    public function test_deleted_or_ineligible_session_promo_is_cleared_and_cannot_be_used_at_checkout(): void
    {
        $product = $this->createProduct();
        $promo = $this->createPromo('GONE10');
        $sessionId = $this->createCartItem($product, $promo->code);
        $this->withCookie(config('session.cookie'), $sessionId);
        $promo->delete();

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Kode promo tidak ditemukan atau sudah tidak tersedia.')
            ->assertDontSee('GONE10')
            ->assertDontSee('- Rp 10.000')
            ->assertSee('Rp 100.000');

        $this->assertFalse(session()->has('promo_code'));

        $promo = $this->createPromo('MIN200', ['min_purchase' => 200000]);
        $this->withSession(['promo_code' => $promo->code])
            ->get(route('checkout'))
            ->assertRedirect(route('cart'))
            ->assertSessionHas('promo_error', 'Subtotal belanja belum memenuhi minimal belanja Rp 200.000.');

        $this->assertFalse(session()->has('promo_code'));
    }

    private function createProduct(): Product
    {
        return Product::create([
            'name' => 'Produk Promo',
            'sku' => 'PROMO-CART-001',
            'price' => 100000,
            'stock' => 10,
            'status' => 'active',
        ]);
    }

    private function createCartItem(Product $product, string $promoCode): string
    {
        $this->withSession(['promo_code' => $promoCode])
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'qty' => 1,
            ])->assertRedirect();

        return session()->getId();
    }

    private function createPromo(string $code, array $attributes = []): Promo
    {
        return Promo::create(array_merge([
            'name' => 'Promo ' . $code,
            'code' => $code,
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => 0,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addWeek(),
            'status' => 'active',
            'popup_enabled' => true,
        ], $attributes));
    }
}
