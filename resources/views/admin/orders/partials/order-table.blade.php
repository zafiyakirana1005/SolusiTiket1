<div class="table-responsive-custom">
    <table class="table table-custom table-hover">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($orders))
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                        No orders found
                    </td>
                </tr>
            @else
                @foreach($orders as $order)
                <tr>
                    <td><strong>#{{ $order['id'] }}</strong></td>
                    <td>{{ $order['customer'] }}</td>
                    <td>{{ $order['items'] }}</td>
                    <td>${{ number_format($order['total'], 2) }}</td>
                    <td>{{ $order['payment'] }}</td>
                    <td>
                        <span class="badge-status {{ $order['status'] }}">
                            {{ ucfirst($order['status']) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($order['date'])->format('d M Y') }}</td>
                    <td>
                        <div class="action-btns d-flex flex-wrap gap-1">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetail('{{ $order['id'] }}')" title="View Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            @if($order['status'] === 'pending')
                                <button class="btn btn-sm btn-outline-success" onclick="updateOrderStatus('{{ $order['id'] }}', 'paid')" title="Mark as Paid">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            
                            @if($order['status'] === 'paid')
                                <button class="btn btn-sm btn-outline-info" onclick="updateOrderStatus('{{ $order['id'] }}', 'processing')" title="Start Processing">
                                    <i class="fas fa-cog"></i>
                                </button>
                            @endif
                            
                            @if($order['status'] === 'processing')
                                <button class="btn btn-sm btn-outline-primary" onclick="addTracking('{{ $order['id'] }}')" title="Add Tracking">
                                    <i class="fas fa-truck"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" onclick="updateOrderStatus('{{ $order['id'] }}', 'shipped')" title="Mark as Shipped">
                                    <i class="fas fa-shipping-fast"></i>
                                </button>
                            @endif
                            
                            @if($order['status'] === 'shipped')
                                <button class="btn btn-sm btn-outline-success" onclick="updateOrderStatus('{{ $order['id'] }}', 'completed')" title="Mark as Completed">
                                    <i class="fas fa-check-double"></i>
                                </button>
                            @endif
                            
                            @if(!in_array($order['status'], ['cancelled', 'completed', 'refunded']))
                                <button class="btn btn-sm btn-outline-danger" onclick="updateOrderStatus('{{ $order['id'] }}', 'cancelled')" title="Cancel Order">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            
                            <button class="btn btn-sm btn-outline-secondary" onclick="printOrder('{{ $order['id'] }}')" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>