<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $needsProcessing = Order::whereIn('fulfillment_status', ['paid', 'packing'])->count();
        $pendingPayment = Order::where('payment_status', 'pending')->count();
        $pendingPaymentValue = Order::where('payment_status', 'pending')->sum('total');

        $activeProducts = Product::where('status', 'active')->count();
        $totalCategories = \App\Models\Category::count();
        $criticalStockProducts = Product::where('status', '!=', 'archived')->get()
            ->filter(fn ($p) => $p->totalStock() < 10)->count();
        $completedOrders = Order::where('fulfillment_status', 'completed')->count();
        $fulfillmentRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

        $recentOrders = Order::with('customer')->latest()->take(6)->get();

        $paidOrderCount = Order::where('payment_status', 'paid')->count();
        $aov = $paidOrderCount > 0 ? intdiv($totalRevenue, $paidOrderCount) : 0;

        return view('admin.dashboard.index', compact(
            'totalRevenue', 'totalOrders', 'needsProcessing', 'pendingPayment', 'pendingPaymentValue',
            'activeProducts', 'totalCategories', 'criticalStockProducts', 'completedOrders',
            'fulfillmentRate', 'recentOrders', 'aov'
        ));
    }
}
