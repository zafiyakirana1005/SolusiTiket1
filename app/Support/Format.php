<?php

namespace App\Support;

class Format
{
    /**
     * Format a number as Indonesian Rupiah, e.g. 1450000 -> "Rp 1.450.000"
     */
    public static function rupiah(?int $value): string
    {
        return 'Rp ' . number_format((int) $value, 0, ',', '.');
    }

    /**
     * Format a number using Indonesian thousands separator, e.g. 1248 -> "1.248"
     */
    public static function number(?int $value): string
    {
        return number_format((int) $value, 0, ',', '.');
    }

    /**
     * Format a date the Indonesian way, e.g. "31 Mei 2024, 14:20 WIB"
     */
    public static function tanggalWaktu($date): string
    {
        if (!$date) {
            return '-';
        }

        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $carbon = \Illuminate\Support\Carbon::parse($date);

        return $carbon->day . ' ' . $bulan[$carbon->month] . ' ' . $carbon->year . ', ' . $carbon->format('H:i') . ' WIB';
    }

    public static function tanggal($date): string
    {
        if (!$date) {
            return '-';
        }

        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $carbon = \Illuminate\Support\Carbon::parse($date);

        return $carbon->day . ' ' . $bulan[$carbon->month] . ' ' . $carbon->year;
    }
}
