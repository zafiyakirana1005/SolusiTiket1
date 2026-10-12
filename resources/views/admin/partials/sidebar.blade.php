@php
  $nav = request()->routeIs('admin.dashboard*') ? 'dashboard'
       : (request()->routeIs('admin.products*') ? 'products'
       : (request()->routeIs('admin.categories*') ? 'categories'
       : (request()->routeIs('admin.orders*') ? 'orders'
       : (request()->routeIs('admin.analytics*') ? 'analytics'
       : (request()->routeIs('admin.promos*') ? 'promos' : '')))));
@endphp
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed left-0 top-0 z-50 flex h-screen w-sidebar-width -translate-x-full transform flex-col bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] transition-transform duration-200 lg:translate-x-0">
  <div class="h-header-height px-unit-lg flex items-center gap-unit-sm bg-surface-container-low/40">
    <div class="h-8 w-8 rounded-lg bg-primary flex items-center justify-center text-on-primary font-bold shrink-0">L</div>
    <div class="flex flex-col min-w-0">
      <span class="font-title-md text-title-md text-primary truncate leading-tight">The Lodge Maribaya</span>
      <span class="font-label-sm text-label-sm text-on-surface-variant truncate">Backoffice Lembang</span>
    </div>
  </div>

  <div class="flex-1 overflow-y-auto px-unit-sm py-unit-md">
    <div class="px-unit-sm mb-unit-xs">
      <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Katalog & Penjualan</span>
    </div>
    <nav class="flex flex-col gap-unit-2xs mb-unit-md">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-unit-sm py-unit-xs rounded-lg transition-colors {{ $nav==='dashboard' ? 'bg-primary-container text-on-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <div class="flex items-center gap-unit-sm min-w-0">
          <span class="material-symbols-outlined text-[20px]">space_dashboard</span>
          <span class="font-label-lg text-label-lg truncate">Dashboard</span>
        </div>
      </a>
      <a href="{{ route('admin.products.index') }}" class="flex items-center justify-between px-unit-sm py-unit-xs rounded-lg transition-colors {{ $nav==='products' ? 'bg-primary-container text-on-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <div class="flex items-center gap-unit-sm min-w-0">
          <span class="material-symbols-outlined text-[20px]">inventory_2</span>
          <span class="font-label-lg text-label-lg truncate">Produk</span>
        </div>
        <span class="px-unit-xs py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">{{ \App\Models\Product::count() }}</span>
      </a>
      <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-between px-unit-sm py-unit-xs rounded-lg transition-colors {{ $nav==='categories' ? 'bg-primary-container text-on-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <div class="flex items-center gap-unit-sm min-w-0">
          <span class="material-symbols-outlined text-[20px]">category</span>
          <span class="font-label-lg text-label-lg truncate">Kategori</span>
        </div>
      </a>
      <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-unit-sm py-unit-xs rounded-lg transition-colors {{ $nav==='orders' ? 'bg-primary-container text-on-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <div class="flex items-center gap-unit-sm min-w-0">
          <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
          <span class="font-label-lg text-label-lg truncate">Pesanan</span>
        </div>
        <span class="px-unit-xs py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">{{ \App\Models\Order::whereIn('fulfillment_status',['pending_payment','paid'])->count() }} Perlu dicek</span>
      </a>
    </nav>

    <div class="px-unit-sm mb-unit-xs">
      <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Pemasaran</span>
    </div>
    <nav class="flex flex-col gap-unit-2xs mb-unit-md">
      <a href="{{ route('admin.promos.index') }}" class="flex items-center justify-between px-unit-sm py-unit-xs rounded-lg transition-colors {{ $nav==='promos' ? 'bg-primary-container text-on-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <div class="flex items-center gap-unit-sm min-w-0">
          <span class="material-symbols-outlined text-[20px]">local_offer</span>
          <span class="font-label-lg text-label-lg truncate">Promo</span>
        </div>
        @php $activePromoCount = \App\Models\Promo::where('status','active')->count(); @endphp
        @if ($activePromoCount)
          <span class="px-unit-xs py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">{{ $activePromoCount }}</span>
        @endif
      </a>
    </nav>

    <div class="px-unit-sm mb-unit-xs">
      <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Laporan</span>
    </div>
    <nav class="flex flex-col gap-unit-2xs mb-unit-md">
      <a href="{{ route('admin.analytics.index') }}" class="flex items-center justify-between px-unit-sm py-unit-xs rounded-lg transition-colors {{ $nav==='analytics' ? 'bg-primary-container text-on-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <div class="flex items-center gap-unit-sm min-w-0">
          <span class="material-symbols-outlined text-[20px]">monitoring</span>
          <span class="font-label-lg text-label-lg truncate">Analitik</span>
        </div>
      </a>
    </nav>
  </div>

  <div class="p-unit-sm bg-surface-container-low/50 flex flex-col gap-unit-xs">
    <div class="p-unit-sm bg-surface-container-lowest rounded-lg flex items-center justify-between">
      <div class="flex items-center gap-unit-xs min-w-0">
        <span class="w-2 h-2 rounded-full bg-secondary shrink-0"></span>
        <div class="flex flex-col min-w-0">
          <span class="font-label-sm text-label-sm text-on-surface font-semibold truncate">Panel Admin</span>
          <span class="font-label-sm text-label-sm text-outline truncate">Kelola produk dan pesanan</span>
        </div>
      </div>
      <span class="material-symbols-outlined text-secondary text-[18px]">admin_panel_settings</span>
    </div>
    @if (Route::has('admin.logout'))
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="w-full flex items-center gap-unit-xs px-unit-sm py-unit-xs rounded-lg text-error hover:bg-error-container/30 hover:text-error transition-colors">
        <span class="material-symbols-outlined text-[20px]">logout</span>
        <span class="font-label-md text-label-md font-semibold">Keluar Sistem</span>
      </button>
    </form>
    @endif
  </div>
</aside>
