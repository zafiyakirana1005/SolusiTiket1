<?php

namespace Tests\Feature;

use App\Models\Promo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoPopupTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_only_renders_active_eligible_promos_enabled_for_popup(): void
    {
        $popupPromo = $this->createPromo([
            'name' => 'Popup Aktif',
            'popup_enabled' => true,
        ]);
        $nonPopupPromo = $this->createPromo([
            'name' => 'Tidak Ditampilkan',
            'popup_enabled' => false,
        ]);
        $inactivePromo = $this->createPromo([
            'name' => 'Promo Nonaktif',
            'popup_enabled' => true,
            'status' => 'inactive',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-promo-popup="'.$popupPromo->id.'"', false)
            ->assertSee('data-slide-toggle', false)
            ->assertSee("const promoPopup = document.querySelector('[data-promo-popup]')", false)
            ->assertDontSee('merchtlm:closed-promo-popup')
            ->assertDontSee('data-promo-popup="'.$nonPopupPromo->id.'"', false)
            ->assertDontSee('data-promo-popup="'.$inactivePromo->id.'"', false);
    }

    public function test_admin_can_create_a_promo_enabled_for_homepage_popup(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.promos.create'))
            ->assertOk()
            ->assertSee('Tampilkan sebagai popup di halaman awal')
            ->assertSee('name="popup_enabled" value="1" checked', false);

        $this->actingAs($admin)
            ->post(route('admin.promos.store'), [
                'name' => 'Promo Beranda',
                'code' => 'BERANDA10',
                'description' => 'Diskon khusus pengunjung.',
                'type' => 'percentage',
                'value' => 10,
                'starts_at' => now()->subDay()->format('Y-m-d H:i:s'),
                'ends_at' => now()->addWeek()->format('Y-m-d H:i:s'),
                'status' => 'active',
                'popup_enabled' => '1',
            ])
            ->assertRedirect(route('admin.promos.index'));

        $this->assertDatabaseHas('promos', [
            'name' => 'Promo Beranda',
            'popup_enabled' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.promos.index'))
            ->assertOk()
            ->assertSee('Promo Beranda')
            ->assertSee('Popup Beranda')
            ->assertSee('Ya');
    }

    private function createPromo(array $attributes = []): Promo
    {
        return Promo::create(array_merge([
            'name' => 'Promo Uji',
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => 0,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addWeek(),
            'status' => 'active',
            'popup_enabled' => false,
        ], $attributes));
    }
}
