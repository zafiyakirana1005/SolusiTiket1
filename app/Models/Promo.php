<?php

namespace App\Models;

use App\Support\Format;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'type', 'value', 'max_discount',
        'min_purchase', 'quota', 'used_count', 'starts_at', 'ends_at', 'status', 'popup_enabled', 'image_path',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'popup_enabled' => 'boolean',
    ];

    /**
     * Status riil promo saat ini, mempertimbangkan tanggal & kuota,
     * bukan cuma kolom 'status' manual.
     */
    public function computedStatus(): string
    {
        if ($this->status === 'inactive') {
            return 'inactive';
        }

        if (Carbon::now()->lt($this->starts_at)) {
            return 'scheduled';
        }

        if (Carbon::now()->gt($this->ends_at)) {
            return 'expired';
        }

        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return 'quota_reached';
        }

        return 'active';
    }

    public function computedStatusLabel(): string
    {
        return match ($this->computedStatus()) {
            'inactive' => 'Dinonaktifkan',
            'scheduled' => 'Terjadwal',
            'expired' => 'Kadaluarsa',
            'quota_reached' => 'Kuota Habis',
            'active' => 'Aktif',
            default => '-',
        };
    }

    public function isRunning(): bool
    {
        return $this->computedStatus() === 'active';
    }

    public function typeLabel(): string
    {
        return $this->type === 'percentage' ? 'Persentase' : 'Nominal Tetap';
    }

    public function formattedValue(): string
    {
        return $this->type === 'percentage'
            ? $this->value . '%'
            : Format::rupiah($this->value);
    }

    public function quotaLabel(): string
    {
        return $this->quota === null
            ? $this->used_count . ' terpakai / Tanpa batas'
            : $this->used_count . ' / ' . $this->quota . ' terpakai';
    }

    /**
     * Hitung berapa potongan (Rupiah) yang berhak didapat untuk subtotal belanja tertentu.
     *
     * @return array{eligible: bool, discount: int, reason: ?string}
     */
    public function calculateDiscount(int $subtotal): array
    {
        if (!$this->isRunning()) {
            return [
                'eligible' => false,
                'discount' => 0,
                'reason' => 'Promo tidak sedang berjalan (status: ' . $this->computedStatusLabel() . ').',
            ];
        }

        if ($subtotal < $this->min_purchase) {
            return [
                'eligible' => false,
                'discount' => 0,
                'reason' => 'Subtotal belanja belum memenuhi minimal belanja ' . Format::rupiah($this->min_purchase) . '.',
            ];
        }

        $discount = $this->type === 'percentage'
            ? (int) floor($subtotal * $this->value / 100)
            : (int) $this->value;

        if ($this->type === 'percentage' && $this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        // Potongan tidak boleh melebihi subtotal itu sendiri.
        $discount = min($discount, $subtotal);

        return [
            'eligible' => true,
            'discount' => $discount,
            'reason' => null,
        ];
    }
}
