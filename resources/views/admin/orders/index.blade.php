@extends('admin.layouts.app')
@section('title', 'Manajemen Pesanan')

@section('content')
@php use App\Support\Format; @endphp

<div class="mt-unit-lg mb-unit-lg">
  <nav class="flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Beranda</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-on-surface font-medium">Antrean Pesanan</span>
  </nav>
  <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight mt-1">Manajemen Pesanan</h1>
  <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Kelola alur transaksi, verifikasi pembayaran, proses pengemasan, dan penerbitan resi pengiriman merchandise Lembang.</p>
</div>

<!-- Status tabs -->
<div class="flex flex-wrap gap-unit-xs mb-unit-md">
  @php
    $tabs = [
      '' => ['Semua Pesanan', $counts['all']],
      'needs_processing' => ['Perlu Diproses', $counts['needs_processing']],
      'pending_payment' => ['Menunggu Bayar', $counts['pending_payment']],
      'paid' => ['Sudah Dibayar', $counts['paid']],
      'packing' => ['Sedang Dikemas', $counts['packing']],
      'ready_to_ship' => ['Siap Dikirim', $counts['ready_to_ship']],
      'shipped' => ['Dalam Perjalanan', $counts['shipped']],
      'completed' => ['Selesai', $counts['completed']],
      'cancelled' => ['Dibatalkan', $counts['cancelled']],
    ];
  @endphp
  @foreach ($tabs as $key => [$label, $count])
    <a href="{{ route('admin.orders.index', array_filter(array_merge(request()->query(), ['status' => $key ?: null]))) }}"
       class="px-unit-sm py-2 rounded-lg font-label-md text-label-md flex items-center gap-1.5 {{ request('status', '') === $key ? 'bg-primary-container text-on-primary font-bold' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container' }}">
      {{ $label }}
      <span class="px-1.5 rounded-full bg-black/10 text-[11px]">{{ $count }}</span>
    </a>
  @endforeach
</div>

<!-- Filter bar -->
<form method="GET" class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-md mb-unit-md flex flex-col lg:flex-row items-stretch lg:items-center gap-unit-sm">
  <input type="hidden" name="status" value="{{ request('status') }}">
  <div class="relative flex-1 min-w-[240px]">
    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
    <input name="q" value="{{ request('q') }}" class="w-full h-10 pl-10 pr-3 bg-surface-container-low rounded-lg" placeholder="Cari no. order / nama pelanggan...">
  </div>
  <select name="courier" class="h-10 pl-unit-sm pr-8 bg-surface-container-low rounded-lg">
    <option value="">Semua Kurir</option>
    @foreach ($couriers as $c)
      <option value="{{ $c }}" @selected(request('courier')==$c)>{{ $c }}</option>
    @endforeach
  </select>
  <select name="payment_method" class="h-10 pl-unit-sm pr-8 bg-surface-container-low rounded-lg">
    <option value="">Semua Metode Bayar</option>
    @foreach ($paymentMethods as $p)
      <option value="{{ $p }}" @selected(request('payment_method')==$p)>{{ $p }}</option>
    @endforeach
  </select>
  <button type="submit" class="h-10 px-unit-md rounded-lg bg-primary text-on-primary font-label-lg text-label-lg font-semibold">Terapkan</button>
  <a href="{{ route('admin.orders.index') }}" class="h-10 w-10 flex items-center justify-center rounded-lg bg-surface-container-low text-outline">
    <span class="material-symbols-outlined text-[18px]">refresh</span>
  </a>
</form>

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-[1080px]">
      <thead>
        <tr class="bg-surface-container-low h-11 text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
          <th class="px-unit-sm py-2">No. Order &amp; Waktu</th>
          <th class="px-unit-sm py-2">Pelanggan &amp; Kontak</th>
          <th class="px-unit-sm py-2">Total Belanja</th>
          <th class="px-unit-sm py-2">Metode Bayar</th>
          <th class="px-unit-sm py-2">Status Bayar</th>
          <th class="px-unit-sm py-2">Status Pengiriman</th>
          <th class="px-unit-sm py-2">Ekspedisi</th>
          <th class="px-unit-md py-2 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container">
        @forelse ($orders as $order)
        <tr class="hover:bg-surface-container-low/60">
          <td class="px-unit-sm py-3 whitespace-nowrap">
            <div class="flex flex-col">
              <span class="font-semibold text-primary">#{{ $order->order_number }}</span>
              <span class="font-label-sm text-label-sm text-outline">{{ Format::tanggalWaktu($order->created_at) }}</span>
            </div>
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap">
            <div class="flex flex-col">
              <span class="font-medium">{{ $order->customer->name }}</span>
              <span class="font-label-sm text-label-sm text-outline">{{ $order->customer->city }}, {{ $order->customer->province }}</span>
            </div>
          </td>
          <td class="px-unit-sm py-3 whitespace-nowrap font-bold">{{ Format::rupiah($order->total) }}</td>
          <td class="px-unit-sm py-3 whitespace-nowrap">{{ $order->payment_method }}</td>
          <td class="px-unit-sm py-3 whitespace-nowrap">@include('admin.partials.payment-badge', ['status'=>$order->payment_status])</td>
          <td class="px-unit-sm py-3 whitespace-nowrap">@include('admin.partials.fulfillment-badge', ['status'=>$order->fulfillment_status])</td>
          <td class="px-unit-sm py-3 whitespace-nowrap">{{ $order->courier }} {{ $order->courier_service }}</td>
          <td class="px-unit-md py-3 text-right whitespace-nowrap">
            <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1 bg-surface-container-low hover:bg-surface-container-high rounded-md font-label-sm text-label-sm text-primary font-semibold">Detail</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="py-8 text-center text-on-surface-variant">Belum ada pesanan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="p-unit-md border-t border-surface-container">{{ $orders->links() }}</div>
</div>
@endsection
