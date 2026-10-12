@extends('admin.layouts.app')
@section('title', 'Produk & Merchandise')

@section('content')
@php use App\Support\Format; @endphp

<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-unit-md mb-unit-lg mt-unit-lg">
  <div class="flex flex-col gap-unit-2xs">
    <nav class="flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
      <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Beranda</a>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-on-surface font-medium">Katalog</span>
    </nav>
    <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight mt-1">Produk &amp; Merchandise</h1>
    <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Kelola katalog merchandise, varian, harga, stok, dan status publikasi toko online The Lodge Maribaya.</p>
  </div>
  <div class="flex items-center gap-unit-sm shrink-0">
    <a href="{{ route('admin.products.create') }}" class="h-10 px-unit-lg rounded-lg bg-primary-container text-on-primary hover:bg-primary transition-all shadow-md flex items-center gap-unit-xs font-label-lg text-label-lg font-semibold">
      <span class="material-symbols-outlined text-[20px]">add</span>
      <span>Tambah Produk Baru</span>
    </a>
  </div>
</div>

<!-- KPI Ribbon -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-unit-sm mb-unit-lg">
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">Semua Produk</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-primary">{{ $countAll }}</div>
  </div>
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">Aktif</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-secondary">{{ $countActive }}</div>
  </div>
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">Draft</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-on-surface">{{ $countDraft }}</div>
  </div>
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-error">Stok Kritis</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-error">{{ $countCritical }}</div>
  </div>
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm col-span-2 sm:col-span-1">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">Diarsipkan</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-on-surface-variant">{{ $countArchived }}</div>
  </div>
</div>

<!-- Filter Toolbar -->
<form method="GET" class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-md mb-unit-md flex flex-col lg:flex-row items-stretch lg:items-center gap-unit-sm">
  <div class="relative flex-1 min-w-[260px] max-w-lg">
    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
    <input name="q" value="{{ request('q') }}" class="w-full h-10 pl-10 pr-3 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none" placeholder="Cari nama produk atau SKU..." type="text"/>
  </div>
  <select name="category" class="h-10 pl-unit-sm pr-8 bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg">
    <option value="">Semua Kategori</option>
    @foreach ($categories as $cat)
      <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
    @endforeach
  </select>
  <select name="status" class="h-10 pl-unit-sm pr-8 bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg">
    <option value="">Semua Status</option>
    <option value="active" @selected(request('status')=='active')>Aktif</option>
    <option value="draft" @selected(request('status')=='draft')>Draft</option>
    <option value="out_of_stock" @selected(request('status')=='out_of_stock')>Stok Habis</option>
    <option value="archived" @selected(request('status')=='archived')>Diarsipkan</option>
  </select>
  <select name="sort" class="h-10 pl-unit-sm pr-8 bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg">
    <option value="latest" @selected(request('sort','latest')=='latest')>Terakhir Diperbarui</option>
    <option value="stok" @selected(request('sort')=='stok')>Stok Terendah</option>
    <option value="harga" @selected(request('sort')=='harga')>Harga Tertinggi</option>
  </select>
  <button type="submit" class="h-10 px-unit-md rounded-lg bg-primary text-on-primary font-label-lg text-label-lg font-semibold">Terapkan</button>
  <a href="{{ route('admin.products.index') }}" class="h-10 w-10 flex items-center justify-center rounded-lg bg-surface-container-low text-outline hover:text-on-surface" title="Reset Filter">
    <span class="material-symbols-outlined text-[18px]">refresh</span>
  </a>
</form>

<!-- Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-[1000px]">
      <thead>
        <tr class="bg-surface-container-low h-11 text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
          <th class="px-unit-sm py-2">Produk &amp; Nama</th>
          <th class="px-unit-sm py-2">Kategori</th>
          <th class="px-unit-sm py-2">Varian</th>
          <th class="px-unit-sm py-2">SKU Utama</th>
          <th class="px-unit-sm py-2 text-right">Harga</th>
          <th class="px-unit-sm py-2 text-center">Total Stok</th>
          <th class="px-unit-sm py-2 text-center">Status</th>
          <th class="px-unit-md py-2 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container">
        @forelse ($products as $product)
        <tr class="hover:bg-surface-container-low/60 transition-colors">
          <td class="px-unit-sm py-3">
            <div class="flex items-center gap-unit-sm">
              <div class="w-12 h-12 rounded-lg bg-surface-container shrink-0 flex items-center justify-center text-outline">
                <span class="material-symbols-outlined text-[22px]">image</span>
              </div>
              <div class="flex flex-col min-w-0">
                <span class="font-title-md text-title-md text-primary font-semibold truncate">{{ $product->name }}</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant truncate">{{ $product->subtitle }}</span>
              </div>
            </div>
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap">
            <span class="px-2.5 py-1 rounded-md bg-surface-container text-on-surface-variant font-label-sm text-label-sm">{{ $product->category->name ?? '—' }}</span>
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap">
            @if ($product->variants->count())
              <div class="flex flex-col">
                <span class="font-label-md text-label-md text-on-surface font-medium">{{ $product->variants->count() }} Varian</span>
                <span class="font-label-sm text-label-sm text-outline">{{ $product->variants->pluck('name')->join(', ') }}</span>
              </div>
            @else
              <span class="text-outline font-label-sm text-label-sm">Tanpa varian</span>
            @endif
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap font-label-md text-label-md text-outline">{{ $product->sku }}</td>
          <td class="px-unit-sm py-3 text-right whitespace-nowrap">
            <div class="font-label-lg text-label-lg font-bold text-on-surface">{{ Format::rupiah($product->price) }}</div>
            @if ($product->compare_at_price)
              <del class="font-label-sm text-label-sm text-outline">{{ Format::rupiah($product->compare_at_price) }}</del>
            @endif
          </td>
          <td class="px-unit-sm py-3 text-center whitespace-nowrap">
            @php $stock = $product->totalStock(); @endphp
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm font-semibold
              {{ $stock <= 0 ? 'bg-error-container text-on-error-container' : ($stock < 10 ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-secondary-container/60 text-secondary') }}">
              {{ $stock }} pcs
            </span>
          </td>
          <td class="px-unit-sm py-3 text-center whitespace-nowrap">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold
              {{ $product->status == 'active' ? 'bg-secondary-container text-on-secondary-container' : ($product->status == 'archived' ? 'bg-surface-container text-on-surface-variant' : 'bg-tertiary-fixed text-on-tertiary-fixed') }}">
              {{ $product->statusLabel() }}
            </span>
          </td>
          <td class="px-unit-md py-3 text-right whitespace-nowrap">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Ubah Produk">
                <span class="material-symbols-outlined text-[18px]">edit</span>
              </a>
              <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-error-container hover:text-error" title="Hapus Produk">
                  <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="py-8 text-center text-on-surface-variant">Belum ada produk. Klik "Tambah Produk Baru" untuk mulai.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="p-unit-md border-t border-surface-container">
    {{ $products->links() }}
  </div>
</div>
@endsection
