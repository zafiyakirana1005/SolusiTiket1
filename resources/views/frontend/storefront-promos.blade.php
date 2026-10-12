<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lihat promo merchandise The Lodge Maribaya yang sedang berlaku.">
    <title>Promo | Merchandise The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        .promo-grid { display:grid; gap:18px; grid-template-columns:repeat(3,minmax(0,1fr)); padding-bottom:72px; }
        .promo-card { background:#fff; border:1px solid var(--store-line); padding:24px; }
        .promo-card h2 { font:400 24px/1.2 var(--store-serif); margin:0 0 12px; }
        .promo-card p { color:var(--store-muted); font-size:14px; line-height:1.55; }
        .promo-value { color:var(--store-rust)!important; font-weight:700; }
        .promo-code { align-items:center; border:1px dashed #b5b7aa; display:flex; justify-content:space-between; margin-top:18px; padding:9px 11px; }
        .promo-code code { color:var(--store-ink); font-weight:700; letter-spacing:1px; }
        .promo-code button { background:none; border:0; color:var(--store-rust); cursor:pointer; font-weight:700; }
        @media(max-width:700px) { .promo-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    @include('partials.header')
    <main class="store-page">
        <div class="store-wrap">
            <header class="store-heading">
                <p class="store-eyebrow">Penawaran pilihan</p>
                <h1>Promo untukmu</h1>
                <p>Nikmati promo merchandise yang sedang berlangsung.</p>
            </header>
            <section class="promo-grid" aria-label="Promo aktif">
                @forelse ($promos as $promo)
                    <article class="promo-card">
                        <p class="promo-value">{{ $promo->formattedValue() }} OFF</p>
                        <h2>{{ $promo->name }}</h2>
                        @if ($promo->description)<p>{{ $promo->description }}</p>@endif
                        <p>Berlaku sampai {{ $promo->ends_at->translatedFormat('j F Y') }}</p>
                        @if ($promo->code)
                            <div class="promo-code"><code>{{ $promo->code }}</code><button type="button" data-copy-code="{{ $promo->code }}">Salin kode</button></div>
                        @else
                            <p>Promo diterapkan otomatis saat checkout.</p>
                        @endif
                    </article>
                @empty
                    <div class="store-empty">Belum ada promo aktif. Jelajahi koleksi merchandise kami.</div>
                @endforelse
                @if ($promos->hasPages())<div class="store-pagination">{{ $promos->links('pagination::bootstrap-5') }}</div>@endif
            </section>
        </div>
    </main>
    @include('partials.footer')
    <script>
        document.querySelectorAll('[data-copy-code]').forEach((button) => {
            button.addEventListener('click', async () => {
                const originalLabel = button.textContent;
                try {
                    await navigator.clipboard.writeText(button.dataset.copyCode);
                    button.textContent = 'Tersalin';
                } catch {
                    button.textContent = 'Salin manual';
                }
                window.setTimeout(() => { button.textContent = originalLabel; }, 1600);
            });
        });
    </script>
</body>
</html>
