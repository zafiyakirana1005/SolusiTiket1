<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title', 'Admin Dashboard') - The Lodge Maribaya</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
        
        <!-- CSS here -->
        <link rel="stylesheet" href="{{ asset('assets/css/preloader.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/jquery.fancybox.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/fontAwesome5Pro.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        
        <!-- Admin Custom CSS -->
        <style>
            /* Admin Sidebar */
            .admin-wrapper {
                display: flex;
                min-height: 100vh;
                background: #f4f6f9;
            }
            .admin-sidebar {
                width: 260px;
                background: #1a1a2e;
                color: #fff;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                overflow-y: auto;
                z-index: 1000;
                transition: all 0.3s ease;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }
            .admin-sidebar::-webkit-scrollbar {
                width: 5px;
            }
            .admin-sidebar::-webkit-scrollbar-thumb {
                background: #e94560;
                border-radius: 10px;
            }
            .admin-sidebar .logo {
                padding: 25px 20px;
                text-align: center;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .admin-sidebar .logo img {
                max-height: 50px;
            }
            .admin-sidebar .logo h4 {
                color: #fff;
                margin-top: 10px;
                font-size: 16px;
            }
            .admin-sidebar .logo h4 span {
                color: #e94560;
            }
            .admin-sidebar .user-info {
                padding: 20px;
                text-align: center;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .admin-sidebar .user-info .avatar {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: #e94560;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 10px;
                font-size: 24px;
                font-weight: bold;
            }
            .admin-sidebar .user-info .name {
                font-size: 14px;
                font-weight: 600;
            }
            .admin-sidebar .user-info .role {
                font-size: 12px;
                color: rgba(255,255,255,0.6);
            }
            .admin-sidebar .nav-menu {
                padding: 15px 0;
            }
            .admin-sidebar .nav-menu .nav-section {
                padding: 10px 20px 5px;
                font-size: 11px;
                text-transform: uppercase;
                color: rgba(255,255,255,0.4);
                letter-spacing: 1px;
            }
            .admin-sidebar .nav-menu .nav-item {
                position: relative;
            }
            .admin-sidebar .nav-menu .nav-item a {
                display: flex;
                align-items: center;
                padding: 12px 20px;
                color: rgba(255,255,255,0.7);
                text-decoration: none;
                transition: all 0.3s ease;
                font-size: 14px;
                border-left: 3px solid transparent;
            }
            .admin-sidebar .nav-menu .nav-item a:hover {
                background: rgba(255,255,255,0.05);
                color: #fff;
                border-left-color: #e94560;
            }
            .admin-sidebar .nav-menu .nav-item a.active {
                background: rgba(233,69,96,0.15);
                color: #fff;
                border-left-color: #e94560;
            }
            .admin-sidebar .nav-menu .nav-item a i {
                width: 24px;
                margin-right: 12px;
                font-size: 16px;
            }
            .admin-sidebar .nav-menu .nav-item a .badge {
                margin-left: auto;
                background: #e94560;
                font-size: 10px;
                padding: 2px 8px;
                border-radius: 10px;
            }
            .admin-sidebar .nav-menu .nav-item .sub-menu {
                list-style: none;
                padding-left: 30px;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }
            .admin-sidebar .nav-menu .nav-item .sub-menu.open {
                max-height: 500px;
            }
            .admin-sidebar .nav-menu .nav-item .sub-menu li a {
                padding: 8px 20px;
                font-size: 13px;
                padding-left: 40px;
            }
            .admin-sidebar .nav-menu .nav-item .sub-menu li a i {
                width: 18px;
                font-size: 12px;
            }

            /* Admin Content */
            .admin-content {
                margin-left: 260px;
                flex: 1;
                padding: 20px;
                min-height: 100vh;
            }
            .admin-content .top-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 20px;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                margin-bottom: 25px;
            }
            .admin-content .top-bar .page-title h4 {
                margin: 0;
                font-weight: 600;
            }
            .admin-content .top-bar .page-title small {
                color: #6c757d;
                font-size: 13px;
            }
            .admin-content .top-bar .top-actions {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            .admin-content .top-bar .top-actions .notification {
                position: relative;
                font-size: 20px;
                color: #6c757d;
                cursor: pointer;
            }
            .admin-content .top-bar .top-actions .notification .badge-dot {
                position: absolute;
                top: -5px;
                right: -5px;
                width: 8px;
                height: 8px;
                background: #e94560;
                border-radius: 50%;
            }

            /* Admin Cards */
            .admin-card {
                background: #fff;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                margin-bottom: 20px;
            }
            .admin-card .card-header-custom {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 15px;
                border-bottom: 1px solid #eee;
                margin-bottom: 15px;
            }
            .admin-card .card-header-custom h5 {
                margin: 0;
                font-weight: 600;
            }

            .stat-card {
                background: #fff;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                transition: transform 0.3s ease;
            }
            .stat-card:hover {
                transform: translateY(-5px);
            }
            .stat-card .stat-icon {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: #fff;
                margin-bottom: 10px;
            }
            .stat-card .stat-icon.primary { background: #4e73df; }
            .stat-card .stat-icon.success { background: #1cc88a; }
            .stat-card .stat-icon.warning { background: #f6c23e; }
            .stat-card .stat-icon.danger { background: #e74a3b; }
            .stat-card .stat-icon.info { background: #36b9cc; }
            .stat-card .stat-number {
                font-size: 28px;
                font-weight: 700;
            }
            .stat-card .stat-label {
                color: #6c757d;
                font-size: 14px;
            }
            .stat-card .stat-change {
                font-size: 12px;
                font-weight: 600;
            }
            .stat-card .stat-change.up { color: #1cc88a; }
            .stat-card .stat-change.down { color: #e74a3b; }

            /* Toggle Sidebar */
            .sidebar-toggle {
                display: none;
                background: none;
                border: none;
                font-size: 24px;
                color: #333;
                cursor: pointer;
            }

            /* Mobile responsive */
            @media (max-width: 992px) {
                .admin-sidebar {
                    transform: translateX(-100%);
                }
                .admin-sidebar.open {
                    transform: translateX(0);
                }
                .admin-content {
                    margin-left: 0;
                }
                .sidebar-toggle {
                    display: block;
                }
                .overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0,0,0,0.5);
                    z-index: 999;
                }
                .overlay.active {
                    display: block;
                }
            }

            /* Form styling */
            .form-group-custom {
                margin-bottom: 20px;
            }
            .form-group-custom label {
                font-weight: 600;
                margin-bottom: 5px;
                font-size: 14px;
            }
            .form-group-custom label .required {
                color: #e74a3b;
            }
            .form-control-custom {
                border-radius: 8px;
                border: 1px solid #e0e0e0;
                padding: 10px 15px;
                transition: border-color 0.3s ease;
            }
            .form-control-custom:focus {
                border-color: #4e73df;
                box-shadow: 0 0 0 3px rgba(78,115,223,0.1);
            }

            /* Table actions */
            .action-btns .btn {
                margin: 0 2px;
                padding: 4px 10px;
                font-size: 12px;
            }

            /* Status badges */
            .badge-status {
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
            }
            .badge-status.active { background: #d4edda; color: #155724; }
            .badge-status.inactive { background: #f8d7da; color: #721c24; }
            .badge-status.draft { background: #fff3cd; color: #856404; }
            .badge-status.paid { background: #d4edda; color: #155724; }
            .badge-status.pending { background: #fff3cd; color: #856404; }
            .badge-status.processing { background: #cce5ff; color: #004085; }
            .badge-status.shipped { background: #d1ecf1; color: #0c5460; }
            .badge-status.delivered { background: #d4edda; color: #155724; }
            .badge-status.cancelled { background: #f8d7da; color: #721c24; }
            .badge-status.refunded { background: #e2e3e5; color: #383d41; }
            .badge-status.expired { background: #f8d7da; color: #721c24; }

            /* Toast notifications */
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            }
            .toast-custom {
                padding: 15px 25px;
                border-radius: 8px;
                color: #fff;
                margin-bottom: 10px;
                animation: slideInRight 0.5s ease;
                min-width: 300px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            }
            .toast-custom.success { background: #1cc88a; }
            .toast-custom.error { background: #e74a3b; }
            .toast-custom.warning { background: #f6c23e; }
            .toast-custom.info { background: #36b9cc; }

            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }

            /* Image preview */
            .image-preview {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 8px;
                border: 2px solid #eee;
                cursor: pointer;
                transition: transform 0.3s ease;
            }
            .image-preview:hover {
                transform: scale(1.05);
            }
            .image-upload-area {
                border: 2px dashed #ddd;
                border-radius: 8px;
                padding: 40px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .image-upload-area:hover {
                border-color: #4e73df;
                background: rgba(78,115,223,0.05);
            }
            .image-upload-area .icon {
                font-size: 40px;
                color: #6c757d;
                margin-bottom: 10px;
            }

            /* Variant management */
            .variant-row {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 10px;
                border: 1px solid #eee;
                position: relative;
            }
            .variant-row .remove-variant {
                position: absolute;
                top: 10px;
                right: 10px;
                background: none;
                border: none;
                color: #e74a3b;
                cursor: pointer;
            }

            /* Responsive table */
            .table-responsive-custom {
                overflow-x: auto;
            }
            .table-custom th {
                background: #f8f9fa;
                font-weight: 600;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 2px solid #eee;
            }
            .table-custom td {
                vertical-align: middle;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <!-- Overlay for mobile -->
        <div class="overlay" id="sidebarOverlay"></div>

        <!-- Admin Wrapper -->
        <div class="admin-wrapper">
            <!-- Sidebar -->
            <aside class="admin-sidebar" id="adminSidebar">
                <div class="logo">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/img/logo/logo-2.png') }}" alt="logo" style="max-height:40px;">
                    </a>
                    <h4>The Lodge <span>Admin</span></h4>
                </div>
                <div class="user-info">
                    <div class="avatar">AD</div>
                    <div class="name">Admin User</div>
                    <div class="role">Super Administrator</div>
                </div>
                <nav class="nav-menu">
                    <div class="nav-section">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="active">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                    </div>

                    <div class="nav-section">Catalog</div>
                    <div class="nav-item">
                        <a href="javascript:void(0);" onclick="toggleSubMenu(this)">
                            <i class="fas fa-box"></i> Products
                            <span class="badge">12</span>
                            <i class="fas fa-chevron-down" style="margin-left:auto;font-size:12px;"></i>
                        </a>
                        <ul class="sub-menu open">
                            <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-list"></i> All Products</a></li>
                            <li><a href="{{ route('admin.products.create') }}"><i class="fas fa-plus"></i> Add Product</a></li>
                            <li><a href="#"><i class="fas fa-tags"></i> Categories</a></li>
                            <li><a href="#"><i class="fas fa-palette"></i> Variants</a></li>
                        </ul>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('inventory.index') }}">
                            <i class="fas fa-warehouse"></i> Inventory
                            <span class="badge">3</span>
                        </a>
                    </div>

                    <div class="nav-section">Sales</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-shopping-bag"></i> Orders
                            <span class="badge">5</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#">
                            <i class="fas fa-credit-card"></i> Payments
                            <span class="badge">2</span>
                        </a>
                    </div>

                    <div class="nav-section">Marketing</div>
                    <div class="nav-item">
                        <a href="#">
                            <i class="fas fa-ticket-alt"></i> Promotions
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#">
                            <i class="fas fa-percent"></i> Vouchers
                        </a>
                    </div>

                    <div class="nav-section">System</div>
                    <div class="nav-item">
                        <a href="#">
                            <i class="fas fa-users-cog"></i> Admin Users
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#">
                            <i class="fas fa-file-alt"></i> Audit Logs
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </div>
                    <div class="nav-item" style="border-top:1px solid rgba(255,255,255,0.1);margin-top:15px;padding-top:15px;">
                        <a href="{{ route('home') }}" target="_blank">
                            <i class="fas fa-store"></i> Visit Store
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="admin-content">
                <!-- Top Bar -->
                <div class="top-bar">
                    <div class="page-title">
                        <button class="sidebar-toggle" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h4>@yield('page-title', 'Dashboard')</h4>
                        <small>@yield('page-subtitle', 'Overview')</small>
                    </div>
                    <div class="top-actions">
                        <div class="notification">
                            <i class="far fa-bell"></i>
                            <span class="badge-dot"></span>
                        </div>
                        <span class="text-muted">|</span>
                        <span class="text-muted small">{{ now()->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <!-- Content -->
                @yield('content')
            </div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- JS here -->
        <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/js/slick.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.fancybox.min.js') }}"></script>
        <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
        <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery-ui-slider-range.js') }}"></script>
        <script src="{{ asset('assets/js/ajax-form.js') }}"></script>
        <script src="{{ asset('assets/js/wow.min.js') }}"></script>
        <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>

        <!-- Admin Custom JS -->
        <script>
            // Sidebar Toggle
            $('#sidebarToggle').on('click', function() {
                $('#adminSidebar').toggleClass('open');
                $('#sidebarOverlay').toggleClass('active');
            });

            $('#sidebarOverlay').on('click', function() {
                $('#adminSidebar').removeClass('open');
                $('#sidebarOverlay').removeClass('active');
            });

            // Sub-menu toggle
            function toggleSubMenu(el) {
                const submenu = $(el).next('.sub-menu');
                submenu.toggleClass('open');
                const icon = $(el).find('.fa-chevron-down');
                if (submenu.hasClass('open')) {
                    icon.css('transform', 'rotate(180deg)');
                } else {
                    icon.css('transform', 'rotate(0deg)');
                }
            }

            // Toast notification
            function showToast(message, type = 'success') {
                const container = $('#toastContainer');
                const toast = $(`
                    <div class="toast-custom ${type}">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-exclamation-circle'}"></i>
                        ${message}
                    </div>
                `);
                container.append(toast);
                setTimeout(() => {
                    toast.fadeOut(500, function() { $(this).remove(); });
                }, 4000);
            }

            // Confirm dialog
            function confirmAction(message, callback) {
                if (confirm(message)) {
                    callback();
                }
            }

            // Active nav link
            $(document).ready(function() {
                const currentUrl = window.location.pathname;
                $('.admin-sidebar .nav-item a').each(function() {
                    if ($(this).attr('href') === currentUrl) {
                        $('.admin-sidebar .nav-item a').removeClass('active');
                        $(this).addClass('active');
                        // Open parent submenu
                        const parent = $(this).closest('.nav-item');
                        if (parent.find('.sub-menu').length) {
                            parent.find('.sub-menu').addClass('open');
                        }
                    }
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>