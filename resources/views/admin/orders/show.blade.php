@extends('admin.layouts.app')
@section('title', 'Pesanan #' . $order->order_number)

@section('content')
@php
  use App\Support\Format;
  $steps = ['pending_payment'=>'Pesanan Dibuat','paid'=>'Pembayaran Diterima','packing'=>'Sedang Dikemas','ready_to_ship' => 'Siap Dikirim','shipped'=>'Dikirim Kurir','completed'=>'Selesai'];
  $order_index = array_search($order->fulfillment_status, array_keys($steps));
  $order_index = $order->fulfillment_status === 'cancelled' ? -1 : $order_index;
@endphp

<div class="mt-unit-lg mb-unit-md">
  <nav class="flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Beranda</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <a href="{{ route('admin.orders.index') }}" class="hover:text-primary">Pesanan</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-on-surface font-medium">#{{ $order->order_number }}</span>
  </nav>

  <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-unit-md mt-1">
    <div>
      <span class="font-label-sm text-label-sm text-outline">ID Sistem: {{ $order->trx_id }}</span>
      <div class="flex items-center flex-wrap gap-unit-sm mt-1">
        <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">Pesanan #{{ $order->order_number }}</h1>
        @include('admin.partials.fulfillment-badge', ['status'=>$order->fulfillment_status])
        @include('admin.partials.payment-badge', ['status'=>$order->payment_status])
      </div>
      <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
        Dibuat pada {{ Format::tanggalWaktu($order->created_at) }} • Sumber: {{ $order->source }}
      </p>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-unit-lg">
  <div class="xl:col-span-2 flex flex-col gap-unit-lg">

    <!-- Fulfillment timeline -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg">
      <h2 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-unit-md">
        <span class="material-symbols-outlined text-secondary">timeline</span>
        Progress Pemenuhan Pesanan
      </h2>
      <div class="flex flex-col gap-unit-sm">
        @foreach ($steps as $key => $label)
          @php $done = $order->fulfillment_status !== 'cancelled' && array_search($key, array_keys($steps)) <= $order_index; @endphp
          <div class="flex items-center gap-unit-sm">
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $done ? 'bg-primary text-on-primary' : 'bg-surface-container text-outline' }}">
              <span class="material-symbols-outlined text-[18px]">{{ $done ? 'check' : 'radio_button_unchecked' }}</span>
            </div>
            <span class="font-label-lg text-label-lg {{ $done ? 'text-on-surface font-semibold' : 'text-outline' }}">{{ $label }}</span>
          </div>
        @endforeach
        @if ($order->fulfillment_status === 'cancelled')
          <div class="flex items-center gap-unit-sm">
            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-error text-on-error"><span class="material-symbols-outlined text-[18px]">close</span></div>
            <span class="font-label-lg text-label-lg text-error font-semibold">Dibatalkan / Refund</span>
          </div>
        @endif
      </div>
    </div>

    <!-- Items -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
      <div class="p-unit-md flex items-center gap-unit-xs">
        <span class="material-symbols-outlined text-secondary">package_2</span>
        <h2 class="font-title-md text-title-md text-primary font-semibold">Rincian Produk Pesanan</h2>
        <span class="px-2 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant">{{ $order->items->count() }} Item</span>
      </div>
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-surface-container-low h-10 text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
            <th class="px-unit-md py-2">Produk &amp; Detail</th>
            <th class="px-unit-sm py-2">Lokasi Rak</th>
            <th class="px-unit-sm py-2 text-right">Harga Satuan</th>
            <th class="px-unit-sm py-2 text-center">Jumlah</th>
            <th class="px-unit-md py-2 text-right">Subtotal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-surface-container">
          @foreach ($order->items as $item)
          <tr>
            <td class="px-unit-md py-3">
              <div class="flex flex-col">
                <span class="font-medium text-on-surface">{{ $item->product_name }}</span>
                <span class="font-label-sm text-label-sm text-outline">{{ $item->variant_name }} @if($item->sku) • SKU: {{ $item->sku }} @endif</span>
              </div>
            </td>
            <td class="px-unit-sm py-3 font-label-sm text-label-sm text-outline">{{ $item->shelf_location ?? '-' }}</td>
            <td class="px-unit-sm py-3 text-right">{{ Format::rupiah($item->unit_price) }}</td>
            <td class="px-unit-sm py-3 text-center">{{ $item->qty }}</td>
            <td class="px-unit-md py-3 text-right font-semibold">{{ Format::rupiah($item->subtotal) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="p-unit-md bg-surface-container-low/40 flex flex-col gap-1">
        <div class="flex items-center justify-between font-label-sm text-label-sm text-on-surface-variant">
          <span>Total Kuantitas: {{ $order->totalQty() }} Produk • Berat: {{ number_format($order->totalWeightGrams()/1000,2) }} kg</span>
        </div>
        <div class="flex items-center justify-between font-label-lg text-label-lg">
          <span class="text-on-surface-variant">Subtotal Produk</span>
          <span class="font-bold">{{ Format::rupiah($order->subtotal) }}</span>
        </div>
        <div class="flex items-center justify-between font-label-lg text-label-lg">
          <span class="text-on-surface-variant">Ongkos Kirim</span>
          <span class="font-bold">{{ Format::rupiah($order->shipping_cost) }}</span>
        </div>
        <div class="flex items-center justify-between font-headline-sm text-headline-sm text-primary pt-1 border-t border-surface-container">
          <span>Total Pesanan</span>
          <span>{{ Format::rupiah($order->total) }}</span>
        </div>
      </div>
    </div>

    @if ($order->buyer_note)
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg">
      <h2 class="font-title-md text-title-md text-primary flex items-center gap-2 mb-unit-sm">
        <span class="material-symbols-outlined text-secondary">sticky_note_2</span> Catatan Pembeli
      </h2>
      <blockquote class="bg-surface-container-low/60 p-unit-sm rounded-lg font-body-md text-body-md italic text-on-surface-variant">
        "{{ $order->buyer_note }}"
      </blockquote>
      <div class="flex items-center gap-unit-sm mt-unit-sm">
        @if ($order->flag_gift_card)
          <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">card_giftcard</span> Kartu Ucapan</span>
        @endif
        @if ($order->flag_extra_wrap)
          <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">shield</span> Extra Bubble Wrap</span>
        @endif
      </div>
    </div>
    @endif

    <!-- Audit trail + notes -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg">
      <h2 class="font-title-md text-title-md text-primary flex items-center gap-2 mb-unit-sm">
        <span class="material-symbols-outlined text-secondary">history_edu</span> Riwayat Aktivitas &amp; Audit Trail
      </h2>
      <div class="flex flex-col gap-unit-sm mb-unit-md">
        @foreach ($order->histories as $h)
          <div class="p-unit-sm bg-surface-container-low/40 rounded-lg">
            <div class="flex items-center justify-between">
              <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $h->actor_name }} @if($h->actor_role)<span class="text-outline font-normal">({{ $h->actor_role }})</span>@endif</span>
              <span class="font-label-sm text-label-sm text-outline">{{ Format::tanggalWaktu($h->created_at) }}</span>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $h->description }}</p>
          </div>
        @endforeach
        @foreach ($order->notes as $n)
          <div class="p-unit-sm bg-tertiary-fixed/30 rounded-lg">
            <div class="flex items-center justify-between">
              <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $n->user->name ?? 'Staff' }} <span class="text-outline font-normal">(Catatan Internal)</span></span>
              <span class="font-label-sm text-label-sm text-outline">{{ Format::tanggalWaktu($n->created_at) }}</span>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $n->note }}</p>
          </div>
        @endforeach
      </div>

      <form action="{{ route('admin.orders.notes.store', $order) }}" method="POST" class="flex items-center gap-unit-xs">
        @csrf
        <input name="note" required class="flex-1 h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="Tambah catatan internal (hanya terlihat tim toko & gudang)...">
        <button type="submit" class="h-10 px-unit-md rounded-lg bg-primary text-on-primary font-label-lg text-label-lg font-semibold flex items-center gap-1">
          <span class="material-symbols-outlined text-[18px]">send</span> Kirim
        </button>
      </form>
    </div>
  </div>

  <!-- Right column: customer info + status update -->
  <div class="flex flex-col gap-unit-lg">
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg">
      <h2 class="font-title-md text-title-md text-primary flex items-center gap-2 mb-unit-sm">
        <span class="material-symbols-outlined text-secondary">person</span> Informasi Pelanggan
      </h2>
      @if ($order->customer->isNewCustomer())
        <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm">Pelanggan Baru (1st Order)</span>
      @endif
      <div class="flex items-center gap-unit-sm mt-unit-sm">
        <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary font-bold">{{ $order->customer->initials() }}</div>
        <div class="flex flex-col">
          <span class="font-label-lg text-label-lg font-semibold">{{ $order->customer->name }}</span>
          <span class="font-label-sm text-label-sm text-outline">{{ $order->customer->email }}</span>
        </div>
      </div>
      <div class="mt-unit-sm pt-unit-sm border-t border-surface-container font-body-sm text-body-sm text-on-surface-variant flex flex-col gap-1">
        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">call</span> {{ $order->customer->phone }}</span>
        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">location_on</span> {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }}</span>
      </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg">
      <h2 class="font-title-md text-title-md text-primary flex items-center gap-2 mb-unit-sm">
        <span class="material-symbols-outlined text-secondary">edit_note</span> Update Status Pesanan
      </h2>
      <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex flex-col gap-unit-sm">
        @csrf
        <div>
          <label class="font-label-md text-label-md text-on-surface-variant">Status Pemenuhan</label>
          <select name="fulfillment_status" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
            @foreach (['pending_payment','paid','packing','ready_to_ship','shipped','completed','cancelled'] as $s)
              <option value="{{ $s }}" @selected($order->fulfillment_status==$s)>{{ \App\Models\Order::fulfillmentStatusLabel($s) }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="font-label-md text-label-md text-on-surface-variant">Kurir</label>
          <input name="courier" value="{{ $order->courier }}" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="JNE / J&T / SiCepat">
        </div>
        <div>
          <label class="font-label-md text-label-md text-on-surface-variant">Nomor Resi (AWB)</label>
          <input name="tracking_number" value="{{ $order->tracking_number }}" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="mis. JNE9821731">
        </div>
        <button type="submit" class="h-10 px-unit-md rounded-lg bg-primary text-on-primary font-label-lg text-label-lg font-semibold mt-1">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
@endsection
