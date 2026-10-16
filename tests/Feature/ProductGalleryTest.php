<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_multiple_additional_product_photos_for_detail_gallery(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('data-add-gallery-image', false)
            ->assertSee('Tambah Foto')
            ->assertDontSee('multiple data-gallery-image-input', false);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Produk Dengan Galeri',
                'sku' => 'GALERI-001',
                'price' => 50000,
                'stock' => 3,
                'status' => 'active',
                'gallery_images' => [
                    UploadedFile::fake()->image('galeri-depan.jpg'),
                    UploadedFile::fake()->image('galeri-samping.jpg'),
                ],
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'GALERI-001')->firstOrFail();
        $this->assertCount(2, $product->images);

        foreach ($product->images as $image) {
            Storage::disk('public')->assertExists($image->image_path);
        }

        $this->get(route('product-details', $product->slug))
            ->assertOk()
            ->assertSee('Pilihan foto produk')
            ->assertSee('Foto tambahan 1')
            ->assertSee('Foto tambahan 2');
    }

    public function test_admin_can_add_and_remove_gallery_images_when_editing_a_product(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::create([
            'name' => 'Produk Galeri Edit',
            'sku' => 'GALERI-EDIT-001',
            'price' => 50000,
            'status' => 'active',
        ]);
        $oldImage = $product->images()->create([
            'image_path' => UploadedFile::fake()->image('lama.jpg')->store('products/gallery', 'public'),
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'status' => 'active',
                'variants_present' => '1',
                'remove_images' => [$oldImage->id],
                'gallery_images' => [UploadedFile::fake()->image('baru.jpg')],
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('product_images', ['id' => $oldImage->id]);
        $this->assertDatabaseCount('product_images', 1);
        Storage::disk('public')->assertMissing($oldImage->image_path);
        Storage::disk('public')->assertExists($product->fresh()->images->first()->image_path);
    }
}
