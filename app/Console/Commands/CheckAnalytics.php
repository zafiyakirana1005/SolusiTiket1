<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use App\Support\Format;
use Illuminate\Console\Command;

class CheckAnalytics extends Command
{
    protected $signature = 'analytics:check';

    protected $description = 'Hitung ulang angka-angka Analitik langsung dari database untuk verifikasi akurasi.';

    public function handle(): int
    {
        $this->info('=== VERIFIKASI DATA ANALITIK ===');
        $this->newLine();

        // ---------- Rincian tiap pesanan ----------
        $orders = Order::with('customer')->orderBy('created_at')->get();

        if ($orders->isEmpty()) {
            $this->warn('Tidak ada data pesanan sama sekali di tabel "orders". Jalankan seeder dulu:');
            $this->line('  php artisan db:seed --class=Database\\Seeders\\AdminDashboardSeeder');
            return self::FAILURE;
        }

        $rows = $orders->map(function (Order $order) {
            return [
                $order->order_number,
                $order->customer->name ?? '-',
                $order->payment_status,
                Format::rupiah($order->total),
                $order->created_at->format('Y-m-d H:i'),
            ];
        });

        $this->table(
            ['No. Order', 'Pelanggan', 'Status Bayar', 'Total', 'Dibuat'],
            $rows
        );

        // ---------- Hitung manual (angka "benar" versi command ini) ----------
        $manualRevenue = $orders->where('payment_status', 'paid')->sum('total');
        $manualOrderCount = $orders->count();
        $manualProductsSold = OrderItem::whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))->sum('qty');

        // ---------- Hitung pakai query yang sama persis dengan AnalyticsController ----------
        $queryRevenue = Order::where('payment_status', 'paid')->sum('total');
        $queryOrderCount = Order::count();
        $queryProductsSold = OrderItem::whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))->sum('qty');

        $this->newLine();
        $this->info('=== PERBANDINGAN ANGKA ===');
        $this->table(
            ['Metrik', 'Hitung Manual (Collection)', 'Hitung via Query (sama dgn Controller)', 'Cocok?'],
            [
                [
                    'Total Pendapatan (paid)',
                    Format::rupiah($manualRevenue),
                    Format::rupiah($queryRevenue),
                    $manualRevenue == $queryRevenue ? '✅ SAMA' : '❌ BEDA',
                ],
                [
                    'Total Pesanan (semua status)',
                    $manualOrderCount,
                    $queryOrderCount,
                    $manualOrderCount == $queryOrderCount ? '✅ SAMA' : '❌ BEDA',
                ],
                [
                    'Total Produk Terjual (paid)',
                    $manualProductsSold,
                    $queryProductsSold,
                    $manualProductsSold == $queryProductsSold ? '✅ SAMA' : '❌ BEDA',
                ],
            ]
        );

        $this->newLine();
        $this->comment('Cocokkan 3 angka di kolom "Hitung via Query" di atas dengan yang tampil');
        $this->comment('di kartu ringkasan halaman /admin/analytics. Kalau sama persis, berarti');
        $this->comment('AnalyticsController sudah akurat mengambil data dari database.');

        // ---------- Cek breakdown per status, untuk sanity-check tambahan ----------
        $this->newLine();
        $this->info('=== BREAKDOWN PER STATUS PEMBAYARAN ===');
        $byStatus = $orders->groupBy('payment_status')->map(fn ($g) => [
            'jumlah' => $g->count(),
            'total' => Format::rupiah($g->sum('total')),
        ]);
        $this->table(
            ['Status', 'Jumlah Pesanan', 'Total Nilai'],
            $byStatus->map(fn ($v, $k) => [$k, $v['jumlah'], $v['total']])->values()
        );

        return self::SUCCESS;
    }
}
