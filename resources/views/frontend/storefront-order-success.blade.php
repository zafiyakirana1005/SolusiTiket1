<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="description" content="Pesanan merchandise The Lodge Maribaya berhasil dibuat.">
    <title>Pesanan dibuat | The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        .order-success { align-items:center; display:flex; justify-content:center; min-height:62vh; padding:56px 18px; }
        .order-success__content { max-width:620px; text-align:center; }
        .order-success__mark { align-items:center; border:1px solid #20211f; border-radius:50%; display:inline-flex; font-size:24px; height:56px; justify-content:center; margin-bottom:22px; width:56px; }
        .order-success h1 { font:400 42px/1.1 var(--store-serif); margin:0 0 14px; }
        .order-success p { color:var(--store-muted); line-height:1.6; }
        .order-success__number { background:#f2f2ef; color:var(--store-ink); display:inline-block; font-size:18px; font-weight:700; letter-spacing:1px; margin:14px 0 24px; padding:15px 22px; }
        .order-success__actions { display:flex; flex-wrap:wrap; gap:12px; justify-content:center; margin-top:22px; }
        @media(max-width:560px) { .order-success h1 { font-size:34px; } }
    </style>
</head>
<body>
    @include('partials.header')
    <main class="store-page order-success">
        <section class="order-success__content" aria-labelledby="order-success-title">
            <span class="order-success__mark" aria-hidden="true">&#10003;</span>
            <p class="store-eyebrow">Terima kasih</p>
            <h1 id="order-success-title">Pesananmu berhasil dibuat.</h1>
            <p>Simpan nomor pesanan ini untuk referensi saat menghubungi tim The Lodge Maribaya.</p>
            <p class="order-success__number">{{ $orderNumber }}</p>
            <div class="order-success__actions">
                <a class="store-button" href="{{ route('shop') }}">Lanjut belanja</a>
                <a class="store-button store-button--outline" href="{{ route('home') }}">Kembali ke beranda</a>
            </div>
        </section>
    </main>
    @include('partials.footer')
</body>
</html>
