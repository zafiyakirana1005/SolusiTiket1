<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'trx_id', 'customer_id', 'subtotal', 'shipping_cost', 'total',
        'payment_method', 'payment_status', 'fulfillment_status', 'inventory_reserved',
        'courier', 'courier_service', 'tracking_number',
        'shipping_address', 'shipping_city', 'shipping_province',
        'buyer_note', 'flag_gift_card', 'flag_extra_wrap',
        'source', 'customer_ip',
    ];

    protected $casts = [
        'flag_gift_card' => 'boolean',
        'flag_extra_wrap' => 'boolean',
        'inventory_reserved' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(OrderNote::class)->latest();
    }

    public function totalWeightGrams(): int
    {
        return (int) $this->items->sum('weight_grams');
    }

    public function totalQty(): int
    {
        return (int) $this->items->sum('qty');
    }

    public static function paymentStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'expired' => 'Expired',
            'refunded' => 'Refund',
            default => ucfirst($status),
        };
    }

    public static function fulfillmentStatusLabel(string $status): string
    {
        return match ($status) {
            'pending_payment' => 'Menunggu Bayar',
            'paid' => 'Sudah Dibayar / Perlu Proses',
            'packing' => 'Sedang Dikemas',
            'ready_to_ship' => 'Siap Dikirim',
            'shipped' => 'Dalam Perjalanan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan / Refund',
            default => ucfirst($status),
        };
    }

    public function paymentStatusLabelAttr(): string
    {
        return self::paymentStatusLabel($this->payment_status);
    }

    public function fulfillmentStatusLabelAttr(): string
    {
        return self::fulfillmentStatusLabel($this->fulfillment_status);
    }
}
