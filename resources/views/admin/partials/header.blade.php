<header class="fixed top-0 left-0 lg:left-[260px] right-0 h-header-height bg-surface/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 px-unit-lg flex items-center justify-between gap-unit-md">
  <div class="flex items-center gap-unit-md flex-1 max-w-xl">
    <button type="button" @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-unit-xs rounded-lg text-on-surface-variant hover:bg-surface-container" aria-label="Buka navigasi admin" :aria-expanded="sidebarOpen.toString()">
      <span class="material-symbols-outlined text-[22px]" aria-hidden="true">menu</span>
    </button>
    <form action="{{ route('admin.orders.index') }}" method="GET" class="relative w-full max-w-md">
      <span class="material-symbols-outlined absolute left-unit-sm top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
      <input name="q" value="{{ request()->routeIs('admin.orders.*') ? request('q') : '' }}" aria-label="Cari nomor pesanan atau pelanggan" class="w-full h-10 pl-9 pr-unit-md bg-surface-container-lowest rounded-lg font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary/40" placeholder="Cari nomor pesanan atau pelanggan..." type="text"/>
    </form>
  </div>
  <div class="flex items-center gap-unit-md shrink-0">
    <div class="flex items-center gap-unit-sm p-unit-2xs pl-unit-xs rounded-full">
      <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary font-label-sm text-label-sm font-bold">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
      </div>
      <div class="hidden md:flex flex-col text-left">
        <span class="font-label-md text-label-md text-on-surface font-semibold leading-tight">{{ auth()->user()->name ?? 'Admin' }}</span>
        <span class="font-label-sm text-label-sm text-on-surface-variant leading-tight">{{ auth()->user()->position ?? 'Operasional Toko' }}</span>
      </div>
      <span class="material-symbols-outlined text-outline text-[18px]">expand_more</span>
    </div>
  </div>
</header>
