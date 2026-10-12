<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'sku', 'stock', 'price_override', 'shelf_location'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function price(): int
    {
        return $this->price_override ?? $this->product->price;
    }
}
