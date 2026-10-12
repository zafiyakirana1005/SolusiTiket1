<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_admin_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.login'))->assertOk()->assertSee('Masuk Admin');
    }

    public function test_admin_can_log_in_and_access_the_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_non_admin_users_cannot_access_admin_pages(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($staff)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), [
                'email' => $staff->email,
                'password' => 'password',
            ])->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');
    }

    public function test_admin_can_log_out(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertSame('admin', $admin->role);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();

        $this->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_processing_queue_only_shows_paid_and_packing_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = Customer::create([
            'name' => 'Pelanggan Uji',
            'email' => 'queue@example.com',
            'phone' => '081234567890',
        ]);

        foreach (['paid', 'packing', 'ready_to_ship'] as $index => $status) {
            Order::create([
                'order_number' => 'ORD-QUEUE-' . $index,
                'customer_id' => $customer->id,
                'subtotal' => 10000,
                'total' => 10000,
                'payment_status' => $status === 'paid' ? 'paid' : 'pending',
                'fulfillment_status' => $status,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.orders.index', ['status' => 'needs_processing']))
            ->assertOk()
            ->assertSee('ORD-QUEUE-0')
            ->assertSee('ORD-QUEUE-1')
            ->assertDontSee('ORD-QUEUE-2');
    }

    public function test_dashboard_average_order_value_uses_only_paid_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = Customer::create([
            'name' => 'Pelanggan AOV',
            'email' => 'aov@example.com',
            'phone' => '081234567891',
        ]);

        foreach ([
            ['ORD-AOV-1', 'paid', 'paid', 20000],
            ['ORD-AOV-2', 'paid', 'paid', 40000],
            ['ORD-AOV-3', 'pending', 'pending_payment', 90000],
        ] as [$number, $paymentStatus, $fulfillmentStatus, $total]) {
            Order::create([
                'order_number' => $number,
                'customer_id' => $customer->id,
                'subtotal' => $total,
                'total' => $total,
                'payment_status' => $paymentStatus,
                'fulfillment_status' => $fulfillmentStatus,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Rata-rata pesanan lunas: Rp 30.000')
            ->assertSee(route('admin.orders.index', ['status' => 'needs_processing']), false)
            ->assertSee(route('admin.orders.index', ['status' => 'pending_payment']), false);
    }
}