@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
@php use App\Support\Format; @endphp

<div class="flex flex-col xl:flex-row xl:items-end justify-between gap-unit-md mb-unit-lg mt-unit-lg">
  <div class="flex flex-col">
    <div class="flex items-center gap-unit-xs mb-1">
      <span class="font-label-sm text-label-sm uppercase tracking-wider text-secondary font-semibold">Ringkasan Operasional</span>
      <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
      <span class="font-label-sm text-label-sm text-on-surface-variant">Data saat ini</span>
    </div>
    <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">Dashboard</h1>
    <p class="font-body-md text-body-md text-on-surface-variant">Pantau pendapatan, pesanan yang perlu diproses, dan kondisi stok produk.</p>
  </div>
</div>

<!-- Primary Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-unit-md mb-unit-md">
  <div class="p-unit-md bg-surface-container-lowest rounded-xl shadow-sm flex flex-col justify-between">
    <div class="flex items-start justify-between">
      <div class="flex flex-col">
        <span class="font-label-md text-label-md text-on-surface-variant">Pendapatan Lunas</span>
        <span class="font-headline-md text-headline-md text-primary mt-1 tracking-tight">{{ Format::rupiah($totalRevenue) }}</span>
      </div>
      <div class="p-2 bg-secondary-container/60 rounded-lg text-primary-container">
        <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
      </div>
    </div>
    <span class="font-body-sm text-body-sm text-on-surface-variant mt-unit-sm">Akumulasi seluruh transaksi lunas</span>
  </div>

  <div class="p-unit-md bg-surface-container-lowest rounded-xl shadow-sm flex flex-col justify-between">
    <div class="flex items-start justify-between">
      <div class="flex flex-col">
        <span class="font-label-md text-label-md text-on-surface-variant">Total Pesanan</span>
        <span class="font-headline-md text-headline-md text-primary mt-1 tracking-tight">{{ Format::number($totalOrders) }} <span class="text-body-md font-normal text-on-surface-variant">Pesanan</span></span>
      </div>
      <div class="p-2 bg-secondary-container/60 rounded-lg text-primary-container">
        <span class="material-symbols-outlined text-[20px]">local_mall</span>
      </div>
    </div>
    <span class="font-body-sm text-body-sm text-on-surface-variant mt-unit-sm">Rata-rata pesanan lunas: {{ Format::rupiah($aov) }}</span>
  </div>

  <a href="{{ route('admin.orders.index', ['status' => 'needs_processing']) }}" class="group block p-unit-md bg-surface-container-lowest rounded-xl shadow-sm transition-shadow hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-secondary">
    <div class="flex items-start justify-between">
      <div class="flex flex-col">
        <span class="font-label-md text-label-md text-on-surface-variant">Perlu Diproses</span>
        <span class="font-headline-md text-headline-md text-primary mt-1 tracking-tight">{{ $needsProcessing }} <span class="text-body-md font-normal text-on-surface-variant">Pesanan</span></span>
      </div>
      <div class="p-2 bg-tertiary-fixed/50 rounded-lg text-tertiary">
        <span class="material-symbols-outlined text-[20px]">inventory</span>
      </div>
    </div>
    <span class="px-2 py-0.5 mt-unit-sm w-fit rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-label-sm font-semibold">Perlu Ditindaklanjuti</span>
    <span class="font-label-sm text-label-sm text-secondary font-semibold mt-2 inline-flex items-center gap-1">Lihat antrean <span aria-hidden="true">&rarr;</span></span>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}" class="group block p-unit-md bg-surface-container-lowest rounded-xl shadow-sm transition-shadow hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-secondary">
    <div class="flex items-start justify-between">
      <div class="flex flex-col">
        <span class="font-label-md text-label-md text-on-surface-variant">Menunggu Bayar</span>
        <span class="font-headline-md text-headline-md text-primary mt-1 tracking-tight">{{ $pendingPayment }} <span class="text-body-md font-normal text-on-surface-variant">Pesanan</span></span>
      </div>
      <div class="p-2 bg-surface-container-high rounded-lg text-on-surface-variant">
        <span class="material-symbols-outlined text-[20px]">schedule</span>
      </div>
    </div>
    <span class="font-body-sm text-body-sm text-outline mt-unit-sm">Nilai belum dibayar: <span class="font-label-md text-label-md text-on-surface font-semibold">{{ Format::rupiah($pendingPaymentValue) }}</span></span>
    <span class="font-label-sm text-label-sm text-secondary font-semibold mt-2 inline-flex items-center gap-1">Lihat pesanan <span aria-hidden="true">&rarr;</span></span>
  </a>
</div>

<!-- Secondary Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-unit-md mb-unit-lg">
  <div class="p-unit-sm bg-surface-container-lowest rounded-xl shadow-sm flex items-center justify-between">
    <div class="flex items-center gap-unit-sm">
      <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-secondary">
        <span class="material-symbols-outlined text-[20px]">category</span>
      </div>
      <div class="flex flex-col">
        <span class="font-label-sm text-label-sm text-outline uppercase">Katalog Toko</span>
        <span class="font-title-md text-title-md text-on-surface font-semibold">{{ $activeProducts }} SKU Aktif</span>
      </div>
    </div>
    <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant">{{ $totalCategories }} Kategori</span>
  </div>

  <div class="p-unit-sm bg-surface-container-lowest rounded-xl shadow-sm flex items-center justify-between">
    <div class="flex items-center gap-unit-sm">
      <div class="w-10 h-10 rounded-lg bg-error-container/70 flex items-center justify-center text-error">
        <span class="material-symbols-outlined text-[20px]">warning</span>
      </div>
      <div class="flex flex-col">
        <span class="font-label-sm text-label-sm text-error font-semibold uppercase">Peringatan Gudang</span>
        <span class="font-title-md text-title-md text-on-surface font-semibold">{{ $criticalStockProducts }} Produk Kritis</span>
      </div>
    </div>
    <span class="px-2 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm font-bold">Perlu Restock</span>
  </div>

  <div class="p-unit-sm bg-surface-container-lowest rounded-xl shadow-sm flex items-center justify-between">
    <div class="flex items-center gap-unit-sm">
      <div class="w-10 h-10 rounded-lg bg-secondary-container/60 flex items-center justify-center text-secondary">
        <span class="material-symbols-outlined text-[20px]">task_alt</span>
      </div>
      <div class="flex flex-col">
        <span class="font-label-sm text-label-sm text-outline uppercase">Pesanan Selesai</span>
        <span class="font-title-md text-title-md text-on-surface font-semibold">{{ Format::number($completedOrders) }} Pesanan</span>
      </div>
    </div>
    <div class="flex items-center gap-1 font-label-md text-label-md text-secondary font-bold">
      <span>{{ $fulfillmentRate }}%</span>
      <span class="font-label-sm text-label-sm text-on-surface-variant font-normal">sukses</span>
    </div>
  </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col">
  <div class="p-unit-md flex items-center gap-unit-xs bg-surface-container-lowest">
    <span class="material-symbols-outlined text-secondary text-[22px]">receipt_long</span>
    <h2 class="font-title-md text-title-md text-primary font-semibold">Pesanan Terbaru</h2>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-surface-container-low/70 text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
          <th class="py-3 px-unit-md">No. Order</th>
          <th class="py-3 px-unit-md">Pelanggan</th>
          <th class="py-3 px-unit-md">Tanggal &amp; Waktu</th>
          <th class="py-3 px-unit-md text-right">Total</th>
          <th class="py-3 px-unit-md">Pembayaran</th>
          <th class="py-3 px-unit-md">Status Order</th>
          <th class="py-3 px-unit-md text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-body-md font-body-md text-on-surface">
        @forelse ($recentOrders as $order)
        <tr class="hover:bg-surface-container-low/40 transition-colors border-t border-surface-container">
          <td class="py-3.5 px-unit-md font-semibold text-primary">#{{ $order->order_number }}</td>
          <td class="py-3.5 px-unit-md">
            <div class="flex flex-col">
              <span class="font-medium text-on-surface">{{ $order->customer->name }}</span>
              <span class="font-label-sm text-label-sm text-outline truncate">{{ $order->customer->city }}</span>
            </div>
          </td>
          <td class="py-3.5 px-unit-md text-on-surface-variant text-body-sm">{{ Format::tanggalWaktu($order->created_at) }}</td>
          <td class="py-3.5 px-unit-md text-right font-bold text-on-surface">{{ Format::rupiah($order->total) }}</td>
          <td class="py-3.5 px-unit-md">{{ $order->payment_method }}</td>
          <td class="py-3.5 px-unit-md">@include('admin.partials.fulfillment-badge', ['status' => $order->fulfillment_status])</td>
          <td class="py-3.5 px-unit-md text-center">
            <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1 bg-surface-container-low hover:bg-surface-container-high rounded-md font-label-sm text-label-sm text-primary font-semibold transition-colors">Detail</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="py-6 px-unit-md text-center text-on-surface-variant">Belum ada pesanan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
