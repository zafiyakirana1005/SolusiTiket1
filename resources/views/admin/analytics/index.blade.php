@extends('admin.layouts.app')
@section('title', 'Analitik')

@section('content')
<div class="mt-unit-lg mb-unit-lg">
  <nav class="flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Beranda</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-on-surface font-medium">Analitik</span>
  </nav>
  <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight mt-1">Dashboard Analitik</h1>
  <p class="font-body-md text-body-md text-on-surface-variant">Ringkasan pendapatan dan aktivitas di platform Anda.</p>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-unit-md mb-unit-lg">
  <div class="p-unit-md bg-surface-container-lowest rounded-xl shadow-sm flex flex-col justify-between">
    <div class="flex items-start justify-between">
      <span class="font-label-md text-label-md text-on-surface-variant">Total Pendapatan</span>
      <div class="p-2 bg-secondary-container/60 rounded-lg text-primary-container">
        <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
      </div>
    </div>
    <span class="font-headline-md text-headline-md text-primary mt-unit-sm tracking-tight">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</span>
  </div>

  <div class="p-unit-md bg-surface-container-lowest rounded-xl shadow-sm flex flex-col justify-between">
    <div class="flex items-start justify-between">
      <span class="font-label-md text-label-md text-on-surface-variant">Total Pesanan</span>
      <div class="p-2 bg-secondary-container/60 rounded-lg text-primary-container">
        <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
      </div>
    </div>
    <span class="font-headline-md text-headline-md text-primary mt-unit-sm tracking-tight">{{ number_format($totalOrders ?? 0, 0, ',', '.') }}</span>
  </div>

  <div class="p-unit-md bg-surface-container-lowest rounded-xl shadow-sm flex flex-col justify-between">
    <div class="flex items-start justify-between">
      <span class="font-label-md text-label-md text-on-surface-variant">Total Produk Terjual</span>
      <div class="p-2 bg-tertiary-fixed/50 rounded-lg text-tertiary">
        <span class="material-symbols-outlined text-[20px]">inventory_2</span>
      </div>
    </div>
    <span class="font-headline-md text-headline-md text-primary mt-unit-sm tracking-tight">{{ number_format($totalProductsSold ?? 0, 0, ',', '.') }}</span>
  </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-unit-md">
  <div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg">
    <h2 class="font-headline-sm text-headline-sm text-primary mb-unit-md">Grafik Pendapatan</h2>
    <canvas id="revenueChart" height="260"></canvas>
  </div>
  <div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg">
    <h2 class="font-headline-sm text-headline-sm text-primary mb-unit-md">Grafik Transaksi</h2>
    <canvas id="transactionChart" height="260"></canvas>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
  const labels      = @json($labels ?? []);
  const revenueData = @json($revenueSeries ?? []);
  const paidData    = @json($paidSeries ?? []);
  const pendingData = @json($pendingSeries ?? []);

  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Pendapatan',
        data: revenueData,
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34,197,94,0.12)',
        tension: 0.4,
        fill: true,
        pointRadius: 3,
        pointBackgroundColor: '#22c55e',
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top' } },
      scales: {
        y: {
          ticks: {
            callback: function (value) {
              if (value >= 1000000) return 'Rp ' + (value / 1000000).toString().replace('.', ',') + ' jt';
              if (value >= 1000) return 'Rp ' + (value / 1000) + ' rb';
              return 'Rp ' + value;
            }
          }
        }
      }
    }
  });

  new Chart(document.getElementById('transactionChart'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        { label: 'Pesanan Lunas', data: paidData, backgroundColor: '#3b82f6' },
        { label: 'Pesanan Pending', data: pendingData, backgroundColor: '#f97316' },
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top' } },
      scales: {
        x: { stacked: true },
        y: { stacked: true, ticks: { precision: 0 } }
      }
    }
  });
</script>
@endpush
