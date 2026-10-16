<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tinjau merchandise pilihanmu sebelum melanjutkan ke checkout.">
    <title>Keranjang Belanja | The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        :root { --ink:#20372d; --muted:#627166; --paper:#f1f3e9; --white:#fbfcf7; --line:#d9e0d2; --rust:#9b5e3d; --serif:Georgia,'Times New Roman',serif; }
        * { box-sizing:border-box; }
        body { background:var(--paper); color:var(--ink); font-family:Arial,'Helvetica Neue',sans-serif; margin:0; }
        .cart-wrap { margin:auto; max-width:1180px; padding:0 24px; }
        .cart-main { min-height:65vh; padding:34px 0 76px; }
        .cart-breadcrumb { color:var(--muted); font-size:13px; margin-bottom:20px; }
        .cart-breadcrumb a { color:var(--ink); text-decoration:none; }
        .cart-title { font:400 38px/1.1 var(--serif); margin:0 0 26px; }
        .cart-layout { align-items:start; display:grid; gap:24px; grid-template-columns:minmax(0,1fr) 310px; }
        .cart-items,.cart-summary { background:var(--white); border:1px solid var(--line); }
        .cart-item { align-items:center; border-bottom:1px solid var(--line); display:grid; gap:12px; grid-template-columns:64px minmax(150px,1fr) 85px 130px 95px 36px; padding:16px; }
        .cart-item:last-child { border-bottom:0; }
        .cart-item img { aspect-ratio:1; background:var(--paper); object-fit:contain; width:64px; }
        .cart-item__name { color:var(--ink); font-size:14px; font-weight:700; line-height:1.4; text-decoration:none; }
        .cart-item__name:hover { color:#3c5d49; }
        .cart-item__variant,.cart-muted { color:var(--muted); font-size:13px; }
        .cart-item__variant { display:block; margin-top:4px; }
        .cart-item__price { color:var(--muted); font-size:13px; white-space:nowrap; }
        .cart-item__subtotal { font-size:14px; white-space:nowrap; }
        .cart-item__qty { display:grid; gap:6px; min-width:0; }
        .cart-item__qty-controls { align-items:center; display:flex; gap:8px; }
        .cart-item__qty output { min-width:20px; text-align:center; }
        .cart-action { background:none; border:0; color:var(--rust); cursor:pointer; font-size:13px; font-weight:700; padding:6px 4px; }
        .cart-action:hover { color:#3c5d49; }
        .cart-item__qty .cart-action { align-items:center; border:1px solid var(--line); color:var(--ink); display:inline-flex; font-size:18px; height:36px; justify-content:center; padding:0; width:36px; }
        .cart-item__qty .cart-action:hover { background:var(--paper); color:#3c5d49; }
        .cart-item__qty .cart-action:disabled { cursor:not-allowed; opacity:.45; }
        .cart-summary { padding:22px; position:sticky; top:24px; }
        .cart-summary h2 { font:400 25px/1.2 var(--serif); margin:0 0 18px; }
        .cart-promo { align-items:stretch; display:flex; gap:8px; margin:12px 0 18px; }
        .cart-promo input { border:1px solid #c4d0c1; min-width:0; padding:9px; width:100%; }
        .cart-summary__line { display:flex; gap:12px; justify-content:space-between; margin:12px 0; }
        .cart-discount { color:#376b4e; }
        .cart-total { border-top:1px solid var(--line); display:flex; font-size:16px; font-weight:700; justify-content:space-between; margin:14px 0 20px; padding-top:14px; }
        .cart-button { align-items:center; background:var(--ink); border:1px solid var(--ink); color:white; display:flex; font-size:14px; font-weight:700; justify-content:center; min-height:46px; padding:0 16px; text-decoration:none; width:100%; }
        .cart-button:hover { background:#3c5d49; color:white; }
        .cart-button--compact { margin:22px auto 0; max-width:220px; }
        .cart-empty { background:var(--white); border:1px solid var(--line); padding:54px 24px; text-align:center; }
        .cart-empty h2 { font:400 28px/1.2 var(--serif); margin:0 0 10px; }
        .cart-empty p { margin:0; }
        .cart-feedback { border:1px solid var(--line); margin:0 0 16px; padding:12px 14px; }
        .cart-feedback--success { background:#edf4ec; border-left:3px solid #3c5d49; }
        .cart-feedback--error { background:#fbefec; border-left:3px solid var(--rust); }
        @media(max-width:900px) { .cart-layout { grid-template-columns:1fr; } .cart-summary { position:static; } }
        @media(max-width:640px) {
            .cart-wrap { padding:0 16px; }
            .cart-main { padding-top:26px; }
            .cart-title { font-size:32px; margin-bottom:20px; }
            .cart-item { align-items:start; column-gap:12px; grid-template-columns:64px minmax(0,1fr) 34px; row-gap:12px; }
            .cart-item img { grid-column:1; grid-row:1 / span 2; width:64px; }
            .cart-item__info { grid-column:2; grid-row:1; }
            .cart-item__remove { grid-column:3; grid-row:1; }
            .cart-item__price,.cart-item__qty,.cart-item__subtotal { grid-column:2 / 4; }
            .cart-item__qty { grid-template-columns:1fr; }
            .cart-item__qty-controls { flex-wrap:nowrap; }
            .cart-item__subtotal { align-self:center; }
            .cart-summary { padding:18px; }
            .cart-empty { padding:42px 20px; }
        }
    </style>
</head>
<body>
    @include('partials.header')
    <main class="cart-main">
        <div class="cart-wrap">
            <h1 class="cart-title">Keranjang belanja</h1>
            @if (session('success'))<p class="cart-feedback cart-feedback--success" role="status">{{ session('success') }}</p>@endif
            @if (session('error'))<p class="cart-feedback cart-feedback--error" role="alert">{{ session('error') }}</p>@endif
            @if ($errors->any())<p class="cart-feedback cart-feedback--error" role="alert">{{ $errors->first() }}</p>@endif
            @if ($items->isEmpty())
                <section class="cart-empty">
                    @if (session('promo_success'))<p class="cart-feedback cart-feedback--success" role="status">{{ session('promo_success') }}</p>@endif
                    @if (session('promo_error'))<p class="cart-feedback cart-feedback--error" role="alert">{{ session('promo_error') }}</p>@endif
                    @if (!empty($promoMessage))<p class="cart-feedback cart-feedback--error" role="alert">{{ $promoMessage }}</p>@endif
                    <h2>Keranjangmu masih kosong</h2>
                    <p class="cart-muted">Jelajahi koleksi merchandise dan pilih yang kamu suka.</p>
                    <a class="cart-button cart-button--compact" href="{{ route('shop') }}">Mulai belanja</a>
                </section>
            @else
                <div class="cart-layout">
                    <section class="cart-items" aria-label="Daftar produk di keranjang">
                        @foreach ($items as $item)
                            @php
                                $itemImage = $item->product->image_path
                                    ? asset('storage/' . $item->product->image_path)
                                    : asset('assets/img/shop/product/product-1.jpg');
                            @endphp
                            <article class="cart-item">
                                <a href="{{ route('product-details', $item->product->slug) }}"><img src="{{ $itemImage }}" alt="{{ $item->product->name }}"></a>
                                <div class="cart-item__info"><a class="cart-item__name" href="{{ route('product-details', $item->product->slug) }}">{{ $item->product->name }}</a>@if ($item->variant_name)<span class="cart-item__variant">{{ $item->variant_name }}</span>@endif</div>
                                <span class="cart-item__price">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                                <form class="cart-item__qty" action="{{ route('cart.update', $item) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <span class="cart-muted">Jumlah</span>
                                    <div class="cart-item__qty-controls">
                                        <button class="cart-action" type="submit" name="qty" value="{{ max(1, $item->qty - 1) }}" aria-label="Kurangi jumlah {{ $item->product->name }}" @disabled($item->qty <= 1)>-</button>
                                        <output aria-label="Jumlah {{ $item->product->name }}">{{ $item->qty }}</output>
                                        <button class="cart-action" type="submit" name="qty" value="{{ $item->qty + 1 }}" aria-label="Tambah jumlah {{ $item->product->name }}">+</button>
                                    </div>
                                </form>
                                <strong class="cart-item__subtotal">Rp {{ number_format($item->subtotal(), 0, ',', '.') }}</strong>
                                <form class="cart-item__remove" action="{{ route('cart.remove', $item) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="cart-action" type="submit" aria-label="Hapus {{ $item->product->name }}">Hapus</button>
                                </form>
                            </article>
                        @endforeach
                    </section>
                    <aside class="cart-summary">
                        <h2>Ringkasan</h2>
                        <form class="cart-promo" action="{{ route('cart.promo.apply') }}" method="POST">
                            @csrf
                            <label class="visually-hidden" for="coupon-code">Kode promo</label>
                            <input id="coupon-code" name="coupon_code" type="text" placeholder="Kode promo" value="{{ $promoCode ?? '' }}">
                            <button class="cart-action" type="submit">Pakai</button>
                        </form>
                        @if ($promoCode)
                            <form action="{{ route('cart.promo.remove') }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="cart-action" type="submit">Hapus kode promo</button>
                            </form>
                        @endif
                        <div class="cart-summary__line"><span>Subtotal</span><strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></div>
                        @if ($discount > 0)<div class="cart-summary__line cart-discount"><span>Diskon</span><strong>- Rp {{ number_format($discount, 0, ',', '.') }}</strong></div>@endif
                        <p class="cart-total"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></p>
                        <a class="cart-button" href="{{ route('checkout') }}">Lanjut ke checkout</a>
                        @if (session('promo_success'))<p class="cart-feedback cart-feedback--success" role="status">{{ session('promo_success') }}</p>@endif
                        @if (session('promo_error'))<p class="cart-feedback cart-feedback--error" role="alert">{{ session('promo_error') }}</p>@endif
                        @if (!empty($promoMessage))<p class="cart-feedback cart-feedback--error" role="alert">{{ $promoMessage }}</p>@endif
                    </aside>
                </div>
            @endif
        </div>
    </main>
    @include('partials.footer')
</body>
</html>
