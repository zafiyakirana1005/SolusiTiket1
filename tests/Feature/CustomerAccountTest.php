<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_customer_login_and_can_open_registration(): void
    {
        $this->get(route('account'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('Selamat datang kembali');
        $this->get(route('register'))->assertOk()->assertSee('Buat akun');
    }

    public function test_registration_creates_a_customer_account_and_profile(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Pelanggan Lodge',
            'email' => 'pelanggan@example.com',
            'phone' => '081234567890',
            'password' => 'password-aman-2026',
            'password_confirmation' => 'password-aman-2026',
        ])->assertRedirect(route('account'));

        $user = User::where('email', 'pelanggan@example.com')->firstOrFail();
        $this->assertSame('customer', $user->role);
        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'name' => 'Pelanggan Lodge',
            'phone' => '081234567890',
        ]);

        $this->get(route('account'))
            ->assertOk()
            ->assertSee('Halo, Pelanggan Lodge')
            ->assertSee('Belum ada pesanan yang terhubung');

        $this->post(route('customer.logout'))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_customer_without_a_profile_can_open_account_and_gets_a_profile_created(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get(route('account'))
            ->assertOk()
            ->assertSee('Halo, ' . $user->name);

        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_customer_login_rejects_admin_credentials(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->actingAs($admin)
            ->get(route('home'))
            ->assertOk()
            ->assertSee(route('admin.dashboard'), false)
            ->assertSee('Dashboard Admin');

        $this->actingAs($admin)
            ->get(route('account'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_account_only_displays_orders_linked_to_its_customer_profile(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $ownCustomer = Customer::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '081200000001',
        ]);
        $guestCustomer = Customer::create([
            'name' => 'Guest Lama',
            'email' => $user->email,
            'phone' => '081200000002',
        ]);
        $this->createOrder($ownCustomer, 'ORD-ACCOUNT-OWN');
        $this->createOrder($guestCustomer, 'ORD-ACCOUNT-GUEST');

        $this->actingAs($user)
            ->get(route('account'))
            ->assertOk()
            ->assertSee('ORD-ACCOUNT-OWN')
            ->assertDontSee('ORD-ACCOUNT-GUEST');
    }

    public function test_customer_checkout_uses_its_profile_and_adds_order_to_account_history(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => 'Akun Lodge',
            'email' => $user->email,
            'phone' => '081200000003',
            'address' => 'Jalan Pinus 1',
            'city' => 'Lembang',
            'province' => 'Jawa Barat',
        ]);
        $product = Product::create([
            'name' => 'Tumbler Lodge',
            'sku' => 'ACCOUNT-001',
            'price' => 25000,
            'stock' => 3,
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post(route('cart.add'), ['product_id' => $product->id, 'qty' => 1])
            ->assertRedirect();
        $sessionId = session()->getId();

        $this->withCookie(config('session.cookie'), $sessionId)
            ->get(route('checkout'))
            ->assertOk()
            ->assertSee('value="Akun Lodge"', false)
            ->assertSee('value="Jalan Pinus 1"', false);

        $this->withCookie(config('session.cookie'), $sessionId)
            ->post(route('checkout.store'), [
                'name' => 'Akun Lodge',
                'email' => $user->email,
                'phone' => '081200000003',
                'address' => 'Jalan Pinus 1',
                'city' => 'Lembang',
                'province' => 'Jawa Barat',
                'courier' => 'JNE',
                'payment_method' => 'Transfer BCA',
            ])
            ->assertRedirect();

        $order = Order::query()->latest('id')->firstOrFail();
        $this->assertSame($customer->id, $order->customer_id);

        $this->get(route('account'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Tumbler Lodge');
    }

    public function test_customer_cart_survives_logout_and_login_and_merges_guest_items(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::create([
            'name' => 'Kaos Persisten',
            'sku' => 'CART-PERSIST-001',
            'price' => 25000,
            'stock' => 10,
            'status' => 'active',
        ]);
        $customerCartId = 'customer:' . $user->id;

        CartItem::create([
            'session_id' => $customerCartId,
            'product_id' => $product->id,
            'unit_price' => 25000,
            'qty' => 1,
        ]);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'qty' => 1])
            ->assertRedirect();
        $guestCartItem = CartItem::query()
            ->where('product_id', $product->id)
            ->where('session_id', '!=', $customerCartId)
            ->firstOrFail();
        $guestSessionId = $guestCartItem->session_id;

        $this->withCookie(config('session.cookie'), $guestSessionId)
            ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('account'));

        $this->assertDatabaseHas('cart_items', [
            'session_id' => $customerCartId,
            'product_id' => $product->id,
            'qty' => 2,
        ]);
        $this->assertDatabaseMissing('cart_items', ['session_id' => $guestSessionId]);

        $this->get(route('cart'))->assertOk()->assertSee('Kaos Persisten');
        $this->post(route('customer.logout'))->assertRedirect(route('login'));

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('account'));

        $this->get(route('cart'))->assertOk()->assertSee('Kaos Persisten');
        $this->assertDatabaseHas('cart_items', [
            'session_id' => $customerCartId,
            'product_id' => $product->id,
            'qty' => 2,
        ]);
    }

    private function createOrder(Customer $customer, string $orderNumber): void
    {
        Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'subtotal' => 10000,
            'shipping_cost' => 0,
            'total' => 10000,
            'payment_method' => 'COD',
        ]);
    }
}