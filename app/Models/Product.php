<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'subtitle', 'description', 'sku',
        'price', 'compare_at_price', 'stock', 'weight_grams', 'image_path', 'image_path_back', 'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(4);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function totalStock(): int
    {
        return $this->variants->count() ? (int) $this->variants->sum('stock') : $this->stock;
    }

    public function stockLabel(): string
    {
        $stock = $this->totalStock();

        return match (true) {
            $stock <= 0 => 'Habis',
            $stock < 10 => 'Menipis',
            default => 'Aman',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'draft' => 'Draft',
            'out_of_stock' => 'Stok Habis',
            'archived' => 'Diarsipkan',
            default => ucfirst($this->status),
        };
    }
}
