<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $pageDescription }}">
    <title>{{ $pageTitle }} | The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        .store-message { align-items:center; display:flex; justify-content:center; min-height:60vh; padding:56px 18px; }
        .store-message__content { max-width:600px; text-align:center; }
        .store-message h1 { font:400 42px/1.1 var(--store-serif); margin:0 0 16px; }
        .store-message p:not(.store-eyebrow) { color:var(--store-muted); line-height:1.65; margin:0 auto; max-width:500px; }
        .store-message__actions { display:flex; flex-wrap:wrap; gap:12px; justify-content:center; margin-top:26px; }
        @media(max-width:560px) { .store-message h1 { font-size:34px; } }
    </style>
</head>
<body>
    @include('partials.header')
    <main class="store-page store-message">
        <section class="store-message__content" aria-labelledby="store-message-title">
            <p class="store-eyebrow">{{ $eyebrow }}</p>
            <h1 id="store-message-title">{{ $pageTitle }}</h1>
            <p>{{ $pageDescription }}</p>
            <div class="store-message__actions">
                <a class="store-button" href="{{ route('shop') }}">Jelajahi produk</a>
                <a class="store-button store-button--outline" href="{{ route('home') }}">Kembali ke beranda</a>
            </div>
        </section>
    </main>
    @include('partials.footer')
</body>
</html>
