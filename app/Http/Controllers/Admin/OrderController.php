<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderNote;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'needs_processing') {
                $query->whereIn('fulfillment_status', ['paid', 'packing']);
            } else {
                $query->where('fulfillment_status', $status);
            }
        }

        if ($courier = $request->get('courier')) {
            $query->where('courier', $courier);
        }

        if ($payment = $request->get('payment_method')) {
            $query->where('payment_method', $payment);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => Order::count(),
            'needs_processing' => Order::whereIn('fulfillment_status', ['paid', 'packing'])->count(),
            'pending_payment' => Order::where('fulfillment_status', 'pending_payment')->count(),
            'paid' => Order::where('fulfillment_status', 'paid')->count(),
            'packing' => Order::where('fulfillment_status', 'packing')->count(),
            'ready_to_ship' => Order::where('fulfillment_status', 'ready_to_ship')->count(),
            'shipped' => Order::where('fulfillment_status', 'shipped')->count(),
            'completed' => Order::where('fulfillment_status', 'completed')->count(),
            'cancelled' => Order::where('fulfillment_status', 'cancelled')->count(),
        ];

        $couriers = Order::whereNotNull('courier')->distinct()->pluck('courier');
        $paymentMethods = Order::whereNotNull('payment_method')->distinct()->pluck('payment_method');

        return view('admin.orders.index', compact('orders', 'counts', 'couriers', 'paymentMethods'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items', 'histories', 'notes.user']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'fulfillment_status' => 'required|in:pending_payment,paid,packing,ready_to_ship,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:50',
            'courier' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($data, $order): void {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            $nextStatus = $data['fulfillment_status'];

            if ($lockedOrder->fulfillment_status === 'cancelled' && $nextStatus !== 'cancelled') {
                throw ValidationException::withMessages(['fulfillment_status' => 'Pesanan yang dibatalkan tidak dapat dilanjutkan kembali.']);
            }

            if ($nextStatus === 'cancelled' && $lockedOrder->payment_status === 'paid') {
                throw ValidationException::withMessages(['fulfillment_status' => 'Pesanan lunas perlu melalui proses refund sebelum dibatalkan.']);
            }

            if ($nextStatus === 'cancelled' && $lockedOrder->inventory_reserved) {
                foreach ($lockedOrder->items()->lockForUpdate()->get() as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::query()->lockForUpdate()->find($item->product_variant_id)?->increment('stock', $item->qty);
                    } elseif ($item->product_id) {
                        Product::query()->lockForUpdate()->find($item->product_id)?->increment('stock', $item->qty);
                    }
                }

                $lockedOrder->inventory_reserved = false;
            }

            $lockedOrder->fill($data);
            if ($nextStatus === 'paid') {
                $lockedOrder->payment_status = 'paid';
            }
            $lockedOrder->save();

            OrderStatusHistory::create([
                'order_id' => $lockedOrder->id,
                'actor_name' => Auth::user()->name,
                'actor_role' => Auth::user()->position ?? 'Staff Operasional',
                'actor_type' => 'staff',
                'description' => 'Mengubah status pesanan menjadi "'.Order::fulfillmentStatusLabel($nextStatus).'".'
                    .(! empty($data['tracking_number']) ? ' Nomor resi: '.$data['tracking_number'].'.' : ''),
            ]);
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function storeNote(Request $request, Order $order)
    {
        $data = $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        OrderNote::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'note' => $data['note'],
        ]);

        return back()->with('success', 'Catatan internal berhasil ditambahkan.');
    }
}
