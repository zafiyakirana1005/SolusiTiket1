@extends('admin.layouts.app')
@section('title', 'Promo & Campaign')

@section('content')
@php use App\Support\Format; @endphp

<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-unit-md mb-unit-lg mt-unit-lg">
  <div class="flex flex-col gap-unit-2xs">
    <nav class="flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
      <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Beranda</a>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-on-surface font-medium">Promo &amp; Campaign</span>
    </nav>
    <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight mt-1">Promo &amp; Campaign</h1>
    <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Kelola diskon, kode voucher, dan campaign promosi untuk toko online The Lodge Maribaya.</p>
  </div>
  <div class="flex items-center gap-unit-sm shrink-0">
    <a href="{{ route('admin.promos.create') }}" class="h-10 px-unit-lg rounded-lg bg-primary-container text-on-primary hover:bg-primary transition-all shadow-md flex items-center gap-unit-xs font-label-lg text-label-lg font-semibold">
      <span class="material-symbols-outlined text-[20px]">add</span>
      <span>Buat Promo Baru</span>
    </a>
  </div>
</div>

<!-- KPI cards -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-unit-sm mb-unit-lg">
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">Total Promo</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-primary">{{ $countAll }}</div>
  </div>
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">Sedang Aktif</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-secondary">{{ $countActive }}</div>
  </div>
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-error">Kadaluarsa/Kuota Habis</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-error">{{ $countExpired }}</div>
  </div>
  <div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-sm">
    <span class="font-label-sm text-label-sm uppercase tracking-wider text-outline">Total Pemakaian</span>
    <div class="mt-unit-sm font-headline-md text-headline-md font-bold text-on-surface">{{ Format::number($totalUsage) }}</div>
  </div>
</div>

<!-- Search -->
<form method="GET" class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-md mb-unit-md flex items-center gap-unit-sm">
  <div class="relative flex-1 max-w-md">
    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
    <input name="q" value="{{ request('q') }}" class="w-full h-10 pl-10 pr-3 bg-surface-container-low rounded-lg font-body-sm text-body-sm" placeholder="Cari nama promo atau kode voucher...">
  </div>
  <button type="submit" class="h-10 px-unit-md rounded-lg bg-primary text-on-primary font-label-lg text-label-lg font-semibold">Cari</button>
  <a href="{{ route('admin.promos.index') }}" class="h-10 w-10 flex items-center justify-center rounded-lg bg-surface-container-low text-outline" title="Reset">
    <span class="material-symbols-outlined text-[18px]">refresh</span>
  </a>
</form>

<!-- Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-[980px]">
      <thead>
        <tr class="bg-surface-container-low h-11 text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
          <th class="px-unit-sm py-2">Nama Promo</th>
          <th class="px-unit-sm py-2">Kode</th>
          <th class="px-unit-sm py-2">Potongan</th>
          <th class="px-unit-sm py-2">Min. Belanja</th>
          <th class="px-unit-sm py-2">Periode</th>
          <th class="px-unit-sm py-2">Kuota</th>
          <th class="px-unit-sm py-2 text-center">Status</th>
          <th class="px-unit-md py-2 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container">
        @forelse ($promos as $promo)
        @php $status = $promo->computedStatus(); @endphp
        <tr class="hover:bg-surface-container-low/60">
          <td class="px-unit-sm py-3">
            <div class="flex flex-col">
              <span class="font-title-md text-title-md text-primary font-semibold">{{ $promo->name }}</span>
              @if ($promo->description)
                <span class="font-body-sm text-body-sm text-on-surface-variant truncate max-w-xs">{{ $promo->description }}</span>
              @endif
            </div>
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap">
            @if ($promo->code)
              <span class="px-2.5 py-1 rounded-md bg-surface-container font-label-md text-label-md font-mono text-on-surface">{{ $promo->code }}</span>
            @else
              <span class="text-outline font-label-sm text-label-sm">Otomatis</span>
            @endif
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap font-label-lg text-label-lg font-bold text-on-surface">
            {{ $promo->formattedValue() }}
            @if ($promo->type === 'percentage' && $promo->max_discount)
              <div class="font-label-sm text-label-sm text-outline font-normal">maks. {{ Format::rupiah($promo->max_discount) }}</div>
            @endif
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap font-body-sm text-body-sm text-on-surface-variant">{{ Format::rupiah($promo->min_purchase) }}</td>
          <td class="px-unit-sm py-3 whitespace-nowrap font-body-sm text-body-sm text-on-surface-variant">
            {{ Format::tanggal($promo->starts_at) }} <br> s/d {{ Format::tanggal($promo->ends_at) }}
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap font-body-sm text-body-sm text-on-surface-variant">{{ $promo->quotaLabel() }}</td>
          <td class="px-unit-sm py-3 text-center whitespace-nowrap">
            @php
              $badgeClass = match($status) {
                'active' => 'bg-secondary-container text-on-secondary-container',
                'scheduled' => 'bg-tertiary-fixed text-on-tertiary-fixed',
                'inactive' => 'bg-surface-container text-on-surface-variant',
                default => 'bg-error-container text-on-error-container',
              };
            @endphp
            <span class="inline-flex px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold {{ $badgeClass }}">{{ $promo->computedStatusLabel() }}</span>
          </td>
          <td class="px-unit-md py-3 text-right whitespace-nowrap">
            <div class="flex items-center justify-end gap-1">
              <form action="{{ route('admin.promos.toggle-status', $promo) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container hover:text-primary" title="{{ $promo->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                  <span class="material-symbols-outlined text-[18px]">{{ $promo->status === 'active' ? 'toggle_on' : 'toggle_off' }}</span>
                </button>
              </form>
              <a href="{{ route('admin.promos.edit', $promo) }}" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Ubah">
                <span class="material-symbols-outlined text-[18px]">edit</span>
              </a>
              <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-error-container hover:text-error" title="Hapus">
                  <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="py-8 text-center text-on-surface-variant">Belum ada promo. Klik "Buat Promo Baru" untuk mulai.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="p-unit-md border-t border-surface-container">{{ $promos->links() }}</div>
</div>
@endsection
