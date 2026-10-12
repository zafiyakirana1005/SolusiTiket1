@extends('admin.layouts.admin')

@section('title', 'Dashboard - The Lodge Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview & Statistics')

@section('content')
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-20">
            <div class="stat-card">
                <div class="stat-icon primary"><i class="fas fa-box"></i></div>
                <div class="stat-number">1,284</div>
                <div class="stat-label">Total Products</div>
                <div class="stat-change up"><i class="fas fa-arrow-up"></i> 12.5%</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-20">
            <div class="stat-card">
                <div class="stat-icon success"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-number">367</div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-change up"><i class="fas fa-arrow-up"></i> 8.2%</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-20">
            <div class="stat-card">
                <div class="stat-icon warning"><i class="fas fa-users"></i></div>
                <div class="stat-number">2,591</div>
                <div class="stat-label">Total Customers</div>
                <div class="stat-change up"><i class="fas fa-arrow-up"></i> 15.3%</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-20">
            <div class="stat-card">
                <div class="stat-icon info"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-number">$45,231</div>
                <div class="stat-label">Revenue</div>
                <div class="stat-change up"><i class="fas fa-arrow-up"></i> 22.1%</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-card">
        <div class="card-header-custom">
            <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('admin.products.create') }}" class="d-block text-center p-4 border rounded hover-shadow transition">
                    <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                    <h6>Add Product</h6>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('admin.orders.index') }}" class="d-block text-center p-4 border rounded hover-shadow transition">
                    <i class="fas fa-shopping-bag fa-2x text-success mb-2"></i>
                    <h6>View Orders</h6>
                    <span class="badge bg-danger">5 New</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('admin.inventory.index') }}" class="d-block text-center p-4 border rounded hover-shadow transition">
                    <i class="fas fa-warehouse fa-2x text-warning mb-2"></i>
                    <h6>Manage Stock</h6>
                    <span class="badge bg-warning text-dark">3 Low Stock</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="#" class="d-block text-center p-4 border rounded hover-shadow transition">
                    <i class="fas fa-credit-card fa-2x text-info mb-2"></i>
                    <h6>View Payments</h6>
                    <span class="badge bg-info text-white">2 Pending</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Low Stock -->
    <div class="row">
        <div class="col-xl-7">
            <div class="admin-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-clock me-2"></i>Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive-custom">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>#ORD-2026-005</strong></td>
                                <td>John Doe</td>
                                <td>$165.00</td>
                                <td><span class="badge-status pending">Pending</span></td>
                                <td>Aug 20, 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2026-004</strong></td>
                                <td>Jane Smith</td>
                                <td>$230.00</td>
                                <td><span class="badge-status processing">Processing</span></td>
                                <td>Aug 19, 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2026-003</strong></td>
                                <td>Robert Johnson</td>
                                <td>$85.00</td>
                                <td><span class="badge-status shipped">Shipped</span></td>
                                <td>Aug 18, 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2026-002</strong></td>
                                <td>Maria Garcia</td>
                                <td>$320.50</td>
                                <td><span class="badge-status delivered">Delivered</span></td>
                                <td>Aug 16, 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2026-001</strong></td>
                                <td>David Wilson</td>
                                <td>$130.00</td>
                                <td><span class="badge-status cancelled">Cancelled</span></td>
                                <td>Aug 15, 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="admin-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert</h5>
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-sm btn-outline-danger">Manage</a>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Wooden Container Bowl</h6>
                            <small class="text-muted">SKU: WCB-001</small>
                        </div>
                        <div>
                            <span class="badge bg-danger">2 left</span>
                            <button class="btn btn-sm btn-outline-primary ms-2" onclick="showToast('Restock Wooden Container Bowl', 'info')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Premium T-Shirt</h6>
                            <small class="text-muted">SKU: PTS-002</small>
                        </div>
                        <div>
                            <span class="badge bg-warning">5 left</span>
                            <button class="btn btn-sm btn-outline-primary ms-2" onclick="showToast('Restock Premium T-Shirt', 'info')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Leather Wallet</h6>
                            <small class="text-muted">SKU: LW-003</small>
                        </div>
                        <div>
                            <span class="badge bg-warning">3 left</span>
                            <button class="btn btn-sm btn-outline-primary ms-2" onclick="showToast('Restock Leather Wallet', 'info')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="admin-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-history me-2"></i>Recent Activity</h5>
                </div>
                <ul class="list-unstyled">
                    <li class="d-flex align-items-start mb-3">
                        <span class="badge bg-primary rounded-circle me-2 p-2">P</span>
                        <div>
                            <small class="text-muted">10 min ago</small>
                            <p class="mb-0">Product "Premium T-Shirt" added by Admin</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <span class="badge bg-success rounded-circle me-2 p-2">O</span>
                        <div>
                            <small class="text-muted">1 hour ago</small>
                            <p class="mb-0">Order #ORD-2026-005 updated to Processing</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <span class="badge bg-warning rounded-circle me-2 p-2">S</span>
                        <div>
                            <small class="text-muted">3 hours ago</small>
                            <p class="mb-0">Stock updated for "Wooden Container Bowl"</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start">
                        <span class="badge bg-danger rounded-circle me-2 p-2">C</span>
                        <div>
                            <small class="text-muted">5 hours ago</small>
                            <p class="mb-0">Order #ORD-2026-001 cancelled by customer</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.transition {
    transition: all 0.3s ease;
}
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-refresh recent orders every 30 seconds (simulated)
        setInterval(function() {
            // In real implementation: fetch new orders via AJAX
            console.log('Checking for new orders...');
        }, 30000);
    });
</script>
@endpush