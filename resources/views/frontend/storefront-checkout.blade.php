<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lengkapi alamat pengiriman dan metode pembayaran untuk pesanan merchandise.">
    <title>Checkout | The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        :root { --ink:#20372d; --muted:#627166; --paper:#f1f3e9; --white:#fbfcf7; --line:#d9e0d2; --rust:#9b5e3d; --serif:Georgia,'Times New Roman',serif; }
        * { box-sizing:border-box; }
        body { background:var(--paper); color:var(--ink); font-family:Arial,'Helvetica Neue',sans-serif; margin:0; }
        .checkout-wrap { margin:auto; max-width:1120px; padding:0 24px; }
        .checkout-main { min-height:65vh; padding:32px 0 76px; }
        .checkout-breadcrumb { color:var(--muted); font-size:13px; margin-bottom:24px; }
        .checkout-breadcrumb a { color:var(--ink); text-decoration:none; }
        .checkout-title { font:400 40px/1.1 var(--serif); margin:0 0 28px; }
        .checkout-grid { align-items:start; display:grid; gap:28px; grid-template-columns:minmax(0,1fr) 380px; }
        .checkout-panel { background:var(--white); border:1px solid var(--line); padding:24px; }
        .checkout-panel h2 { font:400 26px/1.2 var(--serif); margin:0 0 20px; }
        .checkout-fields { display:grid; gap:16px; grid-template-columns:repeat(2,minmax(0,1fr)); }
        .checkout-field--wide { grid-column:1/-1; }
        .checkout-field label { display:block; font-size:13px; font-weight:700; margin-bottom:7px; }
        .checkout-field input,.checkout-field select,.checkout-field textarea { background:var(--white); border:1px solid #c4d0c1; border-radius:2px; color:var(--ink); min-height:44px; padding:10px 11px; width:100%; }
        .checkout-field textarea { min-height:96px; resize:vertical; }
        .checkout-error { color:#a33125; font-size:13px; margin:5px 0 0; }
        .checkout-line { border-bottom:1px solid var(--line); display:flex; gap:12px; justify-content:space-between; padding:12px 0; }
        .checkout-total { border-top:1px solid var(--line); font-size:16px; font-weight:700; margin-top:12px; padding-top:16px; }
        .checkout-total__values { align-items:flex-end; display:flex; flex-direction:column; gap:4px; text-align:right; }
        .checkout-total__before { align-items:baseline; color:var(--muted); display:flex; font-size:12px; font-weight:400; gap:6px; }
        .checkout-button { background:var(--ink); border:1px solid var(--ink); color:white; cursor:pointer; font-size:14px; font-weight:700; margin-top:20px; min-height:48px; width:100%; }
        .checkout-button:hover { background:#3c5d49; }
        @media(max-width:850px) { .checkout-grid { grid-template-columns:1fr; } }
        @media(max-width:560px) { .checkout-wrap { padding:0 16px; } .checkout-title { font-size:34px; } .checkout-panel { padding:18px; } .checkout-fields { grid-template-columns:1fr; } .checkout-field--wide { grid-column:auto; } }
    </style>
</head>
<body>
    @include('partials.header')
    <main class="checkout-main">
        <div class="checkout-wrap">
            <nav class="checkout-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('cart') }}">Keranjang</a> / Checkout</nav>
            <h1 class="checkout-title">Detail pengiriman</h1>
            @if ($errors->has('cart'))<p class="checkout-error" role="alert">{{ $errors->first('cart') }}</p>@endif
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="checkout-grid">
                    <section class="checkout-panel">
                        <h2>Informasi penerima</h2>
                        <div class="checkout-fields">
                            <div class="checkout-field checkout-field--wide"><label for="buyer-name">Nama lengkap *</label><input id="buyer-name" name="name" value="{{ old('name', $customer?->name ?? '') }}" autocomplete="name" required>@error('name')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field"><label for="buyer-email">Email</label><input id="buyer-email" name="email" type="email" value="{{ old('email', $customer?->email ?? '') }}" autocomplete="email">@error('email')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field"><label for="buyer-phone">Nomor HP *</label><input id="buyer-phone" name="phone" type="tel" value="{{ old('phone', $customer?->phone ?? '') }}" autocomplete="tel" required>@error('phone')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field checkout-field--wide"><label for="buyer-address">Alamat lengkap *</label><input id="buyer-address" name="address" value="{{ old('address', $customer?->address ?? '') }}" autocomplete="street-address" required>@error('address')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field"><label for="buyer-city">Kota *</label><input id="buyer-city" name="city" value="{{ old('city', $customer?->city ?? '') }}" autocomplete="address-level2" required>@error('city')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field"><label for="buyer-province">Provinsi *</label><input id="buyer-province" name="province" value="{{ old('province', $customer?->province ?? '') }}" autocomplete="address-level1" required>@error('province')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field"><label for="buyer-courier">Ekspedisi *</label><select id="buyer-courier" name="courier" required><option value="">Pilih ekspedisi</option>@foreach (['JNE', 'J&T', 'SiCepat', 'Anteraja', 'Pos Indonesia'] as $courier)<option value="{{ $courier }}" @selected(old('courier') === $courier)>{{ $courier }}</option>@endforeach</select>@error('courier')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field"><label for="payment-method">Metode pembayaran *</label><select id="payment-method" name="payment_method" required><option value="">Pilih pembayaran</option>@foreach (['Transfer BCA', 'Transfer Mandiri', 'QRIS', 'GoPay', 'OVO', 'Dana', 'COD'] as $method)<option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ $method === 'COD' ? 'COD (Bayar di Tempat)' : $method }}</option>@endforeach</select>@error('payment_method')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                            <div class="checkout-field checkout-field--wide"><label for="buyer-note">Catatan untuk toko (opsional)</label><textarea id="buyer-note" name="buyer_note">{{ old('buyer_note') }}</textarea>@error('buyer_note')<p class="checkout-error">{{ $message }}</p>@enderror</div>
                        </div>
                    </section>
                    <aside class="checkout-panel">
                        <h2>Ringkasan pesanan</h2>
                        @foreach ($items as $item)
                            <div class="checkout-line"><span>{{ $item->product->name }}@if ($item->variant_name) <small>({{ $item->variant_name }})</small>@endif × {{ $item->qty }}</span><strong>Rp {{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}</strong></div>
                        @endforeach
                        <div class="checkout-line"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                        @if ($discount > 0)<div class="checkout-line"><span>Diskon</span><span>- Rp {{ number_format($discount, 0, ',', '.') }}</span></div>@endif
                        <div class="checkout-line"><span>Ongkos kirim</span><span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span></div>
                        <div class="checkout-line checkout-total">
                            <span>Total</span>
                            <span class="checkout-total__values">
                                @if ($discount > 0)
                                    <span class="checkout-total__before"><small>Sebelum diskon</small><del>Rp {{ number_format($subtotal + $shippingCost, 0, ',', '.') }}</del></span>
                                @endif
                                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </span>
                        </div>
                        <button class="checkout-button" type="submit">Buat pesanan</button>
                        <p style="color:var(--muted);font-size:12px;line-height:1.5">Dengan melanjutkan, data pengiriman akan digunakan untuk memproses pesanan ini.</p>
                    </aside>
                </div>
            </form>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>
