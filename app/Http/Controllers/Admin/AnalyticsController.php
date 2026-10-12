<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $days = 30;
        $dateRange = collect(range(0, $days - 1))
            ->map(fn ($i) => Carbon::today()->subDays($days - 1 - $i))
            ->values();

        $start = $dateRange->first()->startOfDay();
        $end = $dateRange->last()->endOfDay();

        // ---------- Kartu Ringkasan ----------
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalProductsSold = OrderItem::whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))->sum('qty');

        // ---------- Grafik Pendapatan (harian, hanya pesanan lunas) ----------
        $revenueRows = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, SUM(total) as total')
            ->groupBy('d')
            ->pluck('total', 'd');

        $revenueSeries = $dateRange->map(fn ($date) => (int) ($revenueRows[$date->format('Y-m-d')] ?? 0))->values();

        // ---------- Grafik Transaksi (harian, Pesanan Lunas vs Pesanan Pending) ----------
        $paidRows = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as jumlah')
            ->groupBy('d')
            ->pluck('jumlah', 'd');

        $pendingRows = Order::where('payment_status', 'pending')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as jumlah')
            ->groupBy('d')
            ->pluck('jumlah', 'd');

        $paidSeries = $dateRange->map(fn ($date) => (int) ($paidRows[$date->format('Y-m-d')] ?? 0))->values();
        $pendingSeries = $dateRange->map(fn ($date) => (int) ($pendingRows[$date->format('Y-m-d')] ?? 0))->values();

        $labels = $dateRange->map(fn ($d) => $d->translatedFormat('j M'))->values();

        return view('admin.analytics.index', [
            'labels' => $labels,
            'revenueSeries' => $revenueSeries,
            'paidSeries' => $paidSeries,
            'pendingSeries' => $pendingSeries,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalProductsSold' => $totalProductsSold,
        ]);
    }
}
