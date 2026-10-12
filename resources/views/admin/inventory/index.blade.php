@extends('admin.layouts.admin')

@section('title', 'Inventory - The Lodge Admin')
@section('page-title', 'Inventory')
@section('page-subtitle', 'Manage stock levels')

@section('content')
<div class="container-fluid">
    <!-- Stats -->
    <div class="row">
        <div class="col-md-3 mb-20">
            <div class="stat-card">
                <div class="stat-number">1,284</div>
                <div class="stat-label">Total SKUs</div>
            </div>
        </div>
        <div class="col-md-3 mb-20">
            <div class="stat-card">
                <div class="stat-number" style="color:#1cc88a;">8,450</div>
                <div class="stat-label">Total Stock</div>
            </div>
        </div>
        <div class="col-md-3 mb-20">
            <div class="stat-card">
                <div class="stat-number" style="color:#f6c23e;">23</div>
                <div class="stat-label">Low Stock (≤5)</div>
            </div>
        </div>
        <div class="col-md-3 mb-20">
            <div class="stat-card">
                <div class="stat-number" style="color:#e74a3b;">8</div>
                <div class="stat-label">Out of Stock</div>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="admin-card">
        <div class="card-header-custom">
            <h5><i class="fas fa-warehouse me-2"></i>Stock Management</h5>
            <div>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                    <i class="fas fa-plus"></i> Adjust Stock
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <div class="table-responsive-custom">
            <table class="table table-custom table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>On Hand</th>
                        <th>Reserved</th>
                        <th>Available</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $inventory = [
                            ['product' => 'Wooden Container Bowl', 'sku' => 'WCB-001', 'on_hand' => 2, 'reserved' => 1, 'available' => 1, 'status' => 'low', 'updated' => '2026-08-20 14:30'],
                            ['product' => 'Premium T-Shirt', 'sku' => 'PTS-002', 'on_hand' => 5, 'reserved' => 2, 'available' => 3, 'status' => 'low', 'updated' => '2026-08-20 13:15'],
                            ['product' => 'Leather Wallet', 'sku' => 'LW-003', 'on_hand' => 3, 'reserved' => 0, 'available' => 3, 'status' => 'low', 'updated' => '2026-08-20 12:00'],
                            ['product' => 'Ceramic Mug', 'sku' => 'CM-004', 'on_hand' => 0, 'reserved' => 0, 'available' => 0, 'status' => 'out', 'updated' => '2026-08-19 16:45'],
                            ['product' => 'Cotton Tote Bag', 'sku' => 'CTB-005', 'on_hand' => 45, 'reserved' => 5, 'available' => 40, 'status' => 'good', 'updated' => '2026-08-19 10:20'],
                            ['product' => 'Stainless Steel Bottle', 'sku' => 'SSB-006', 'on_hand' => 8, 'reserved' => 2, 'available' => 6, 'status' => 'good', 'updated' => '2026-08-18 09:00'],
                        ];
                    @endphp
                    @foreach($inventory as $item)
                    <tr>
                        <td><strong>{{ $item['product'] }}</strong></td>
                        <td><code>{{ $item['sku'] }}</code></td>
                        <td>{{ $item['on_hand'] }}</td>
                        <td>{{ $item['reserved'] }}</td>
                        <td>
                            <strong class="{{ $item['available'] <= 0 ? 'text-danger' : ($item['available'] <= 5 ? 'text-warning' : 'text-success') }}">
                                {{ $item['available'] }}
                            </strong>
                        </td>
                        <td>
                            @if($item['status'] === 'out')
                                <span class="badge bg-danger">Out of Stock</span>
                            @elseif($item['status'] === 'low')
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            @else
                                <span class="badge bg-success">In Stock</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item['updated'])->diffForHumans() }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="adjustStock('{{ $item['sku'] }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="viewStockHistory('{{ $item['sku'] }}')">
                                <i class="fas fa-history"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Adjust Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="adjustStockForm">
                <div class="modal-body">
                    <div class="form-group-custom">
                        <label>Product/SKU <span class="required">*</span></label>
                        <select class="form-select form-control-custom" required>
                            <option value="">Select Product</option>
                            @foreach($inventory as $item)
                                <option value="{{ $item['sku'] }}">{{ $item['product'] }} ({{ $item['sku'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label>Adjustment Type <span class="required">*</span></label>
                        <select class="form-select form-control-custom" id="adjustmentType" required>
                            <option value="add">Add Stock (+) </option>
                            <option value="subtract">Remove Stock (-)</option>
                            <option value="set">Set to Exact Value</option>
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label>Quantity <span class="required">*</span></label>
                        <input type="number" class="form-control form-control-custom" 
                               placeholder="Enter quantity" required min="1">
                    </div>
                    <div class="form-group-custom">
                        <label>Reason <span class="required">*</span></label>
                        <select class="form-select form-control-custom" required>
                            <option value="">Select Reason</option>
                            <option value="restock">Restock / New Shipment</option>
                            <option value="sale">Sale / Order Fulfillment</option>
                            <option value="return">Return / Refund</option>
                            <option value="damage">Damage / Loss</option>
                            <option value="correction">Manual Correction</option>
                            <option value="transfer">Stock Transfer</option>
                        </select>
                    </div>
                    <div class="form-group-custom">
                        <label>Notes (Optional)</label>
                        <textarea class="form-control form-control-custom" rows="2" 
                                  placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Apply Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function adjustStock(sku) {
        $('#adjustStockModal select[name="product"]').val(sku);
        $('#adjustStockModal').modal('show');
    }

    function viewStockHistory(sku) {
        showToast(`Viewing stock history for ${sku}`, 'info');
    }

    $('#adjustStockForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Processing...');
        btn.prop('disabled', true);

        setTimeout(function() {
            btn.html('<i class="fas fa-check me-2"></i> Stock Updated!');
            btn.removeClass('btn-primary').addClass('btn-success');
            showToast('Stock adjustment applied successfully!', 'success');
            
            setTimeout(function() {
                $('#adjustStockModal').modal('hide');
                window.location.reload();
            }, 1500);
        }, 1500);
    });
</script>
@endpush
@endsection 