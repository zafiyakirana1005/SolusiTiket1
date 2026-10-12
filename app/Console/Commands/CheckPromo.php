<?php

namespace App\Console\Commands;

use App\Models\Promo;
use App\Support\Format;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckPromo extends Command
{
    protected $signature = 'promo:check';

    protected $description = 'Buat beberapa skenario promo contoh, tes logika status & hitungan diskonnya, lalu bersihkan lagi.';

    public function handle(): int
    {
        $this->info('=== TES LOGIKA PROMO / DISKON ===');
        $this->newLine();

        $testTag = 'TEST-PROMO-' . now()->timestamp;

        $scenarios = [
            [
                'label' => 'Persentase aktif, dengan batas maksimal',
                'name' => $testTag . ' Diskon 20%',
                'code' => $testTag . '-A',
                'type' => 'percentage',
                'value' => 20,
                'max_discount' => 50000,
                'min_purchase' => 100000,
                'quota' => 10,
                'used_count' => 0,
                'starts_at' => Carbon::now()->subDay(),
                'ends_at' => Carbon::now()->addWeek(),
                'status' => 'active',
                'expected_status' => 'active',
                'test_subtotals' => [100000, 300000, 1000000, 50000],
            ],
            [
                'label' => 'Nominal tetap aktif',
                'name' => $testTag . ' Potongan Rp 25.000',
                'code' => $testTag . '-B',
                'type' => 'fixed',
                'value' => 25000,
                'max_discount' => null,
                'min_purchase' => 50000,
                'quota' => null,
                'used_count' => 0,
                'starts_at' => Carbon::now()->subDay(),
                'ends_at' => Carbon::now()->addWeek(),
                'status' => 'active',
                'expected_status' => 'active',
                'test_subtotals' => [50000, 20000],
            ],
            [
                'label' => 'Sudah kadaluarsa (ends_at kemarin)',
                'name' => $testTag . ' Sudah Lewat',
                'code' => $testTag . '-C',
                'type' => 'percentage',
                'value' => 10,
                'max_discount' => null,
                'min_purchase' => 0,
                'quota' => null,
                'used_count' => 0,
                'starts_at' => Carbon::now()->subWeeks(2),
                'ends_at' => Carbon::now()->subDay(),
                'status' => 'active',
                'expected_status' => 'expired',
                'test_subtotals' => [100000],
            ],
            [
                'label' => 'Belum mulai (starts_at besok)',
                'name' => $testTag . ' Belum Mulai',
                'code' => $testTag . '-D',
                'type' => 'percentage',
                'value' => 15,
                'max_discount' => null,
                'min_purchase' => 0,
                'quota' => null,
                'used_count' => 0,
                'starts_at' => Carbon::now()->addDay(),
                'ends_at' => Carbon::now()->addWeek(),
                'status' => 'active',
                'expected_status' => 'scheduled',
                'test_subtotals' => [100000],
            ],
            [
                'label' => 'Kuota sudah habis',
                'name' => $testTag . ' Kuota Habis',
                'code' => $testTag . '-E',
                'type' => 'fixed',
                'value' => 10000,
                'max_discount' => null,
                'min_purchase' => 0,
                'quota' => 5,
                'used_count' => 5,
                'starts_at' => Carbon::now()->subDay(),
                'ends_at' => Carbon::now()->addWeek(),
                'status' => 'active',
                'expected_status' => 'quota_reached',
                'test_subtotals' => [100000],
            ],
            [
                'label' => 'Dinonaktifkan manual',
                'name' => $testTag . ' Nonaktif',
                'code' => $testTag . '-F',
                'type' => 'percentage',
                'value' => 10,
                'max_discount' => null,
                'min_purchase' => 0,
                'quota' => null,
                'used_count' => 0,
                'starts_at' => Carbon::now()->subDay(),
                'ends_at' => Carbon::now()->addWeek(),
                'status' => 'inactive',
                'expected_status' => 'inactive',
                'test_subtotals' => [100000],
            ],
        ];

        $createdIds = [];
        $rows = [];
        $statusAllPass = true;

        foreach ($scenarios as $s) {
            $promo = Promo::create([
                'name' => $s['name'],
                'code' => $s['code'],
                'type' => $s['type'],
                'value' => $s['value'],
                'max_discount' => $s['max_discount'],
                'min_purchase' => $s['min_purchase'],
                'quota' => $s['quota'],
                'used_count' => $s['used_count'],
                'starts_at' => $s['starts_at'],
                'ends_at' => $s['ends_at'],
                'status' => $s['status'],
            ]);
            $createdIds[] = $promo->id;

            $actualStatus = $promo->computedStatus();
            $statusMatch = $actualStatus === $s['expected_status'];
            $statusAllPass = $statusAllPass && $statusMatch;

            $rows[] = [
                $s['label'],
                $s['expected_status'],
                $actualStatus,
                $statusMatch ? '✅' : '❌',
            ];

            $this->line('');
            $this->info('Skenario: ' . $s['label']);
            foreach ($s['test_subtotals'] as $subtotal) {
                $result = $promo->calculateDiscount($subtotal);
                $this->line(sprintf(
                    '  Subtotal %-15s -> %s%s',
                    Format::rupiah($subtotal),
                    $result['eligible'] ? 'Potongan ' . Format::rupiah($result['discount']) : 'TIDAK BERLAKU',
                    $result['reason'] ? ' (' . $result['reason'] . ')' : ''
                ));
            }
        }

        $this->newLine();
        $this->info('=== HASIL CEK STATUS (expected vs actual) ===');
        $this->table(['Skenario', 'Status Diharapkan', 'Status Aktual', 'Cocok?'], $rows);

        // Cleanup - hapus semua promo tes yang baru dibuat supaya tidak nyampah
        Promo::whereIn('id', $createdIds)->delete();
        $this->newLine();
        $this->comment(count($createdIds) . ' promo tes sudah dihapus lagi dari database (cleanup selesai).');

        $this->newLine();
        if ($statusAllPass) {
            $this->info('✅ SEMUA skenario status LULUS. Logika computedStatus() di model Promo sudah benar.');
        } else {
            $this->error('❌ Ada skenario yang statusnya TIDAK sesuai harapan. Cek kolom "Cocok?" di tabel di atas.');
        }

        return $statusAllPass ? self::SUCCESS : self::FAILURE;
    }
}
