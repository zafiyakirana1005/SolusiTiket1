<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f1f3e9">
    <meta name="description" content="Temukan merchandise resmi The Lodge Maribaya. Pilih koleksi apparel, tumbler, dan souvenir untuk membawa pulang kenangan dari alam.">
    <link rel="canonical" href="{{ route('home') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <title>Merchandise The Lodge Maribaya | Koleksi Resmi</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        :root {
            color-scheme: light;
            --ink: #20372d;
            --muted: #627166;
            --paper: #f1f3e9;
            --white: #fbfcf7;
            --line: #d9e0d2;
            --rust: #9b5e3d;
            --sage: #e2e9dc;
            --serif: Georgia, 'Times New Roman', serif;
            --sans: 'Instrument Sans', 'Segoe UI', sans-serif;
        }
        * { box-sizing: border-box; }
        body { background: var(--paper); color: var(--ink); font-family: var(--sans); margin: 0; }
        a { color: inherit; }
        a:focus-visible, button:focus-visible, input:focus-visible { outline: 3px solid #b8784e; outline-offset: 3px; }
        .tl-wrap { margin: 0 auto; max-width: 1240px; padding: 0 28px; width: 100%; }
        .tl-slider { background: #183c2c; min-height: 590px; overflow: hidden; position: relative; }
        .tl-slide { align-items: center; display: flex; inset: 0; min-height: 590px; opacity: 0; position: absolute; transition: opacity .55s ease; visibility: hidden; }
        .tl-slide.is-active { opacity: 1; visibility: visible; z-index: 1; }
        .tl-slide__image { height: 100%; inset: 0; object-fit: cover; object-position: center; position: absolute; width: 100%; }
        .tl-slide::after { background: linear-gradient(90deg, rgba(14, 37, 25, .78), rgba(14, 37, 25, .22)); content: ''; inset: 0; position: absolute; z-index: 1; }
        .tl-slide__content { margin: 0 auto; max-width: 1240px; padding: 70px 28px 110px; position: relative; width: 100%; z-index: 2; }
        .tl-slide__content-inner { max-width: 610px; }
        .tl-eyebrow { color: var(--rust); display: inline-block; font-size: 12px; font-weight: 700; letter-spacing: 1.5px; margin: 0 0 18px; text-transform: uppercase; }
        .tl-slide .tl-eyebrow { color: #e2c39f; }
        .tl-slide h1, .tl-slide h2 { color: #fff; font: 400 64px/1.04 var(--serif); margin: 0 0 20px; max-width: 610px; }
        .tl-slide__copy { color: #f3f3ed; font-size: 17px; line-height: 1.7; margin: 0 0 30px; max-width: 510px; }
        .tl-actions { align-items: center; display: flex; flex-wrap: wrap; gap: 12px; }
        .tl-button { align-items: center; background: var(--ink); border: 1px solid var(--ink); border-radius: 2px; color: var(--white); display: inline-flex; font-size: 14px; font-weight: 700; justify-content: center; min-height: 48px; padding: 0 22px; text-decoration: none; transition: background .2s ease, transform .2s ease; }
        .tl-button:hover { background: #3c5d49; color: var(--white); transform: translateY(-2px); }
        .tl-button--outline { background: transparent; color: var(--ink); }
        .tl-button--outline:hover { background: var(--ink); color: var(--white); }
        .tl-slide .tl-button { background: #fbfcf7; border-color: #fbfcf7; color: #20372d; }
        .tl-slide .tl-button:hover { background: #e2c39f; border-color: #e2c39f; }
        .tl-slider__controls { align-items: center; bottom: 22px; display: flex; gap: 12px; position: absolute; right: max(22px, calc((100% - 1184px) / 2)); z-index: 3; }
        .tl-slider__arrow, .tl-slider__toggle { background: rgba(20, 33, 25, .7); border: 1px solid rgba(255, 255, 255, .65); color: #fff; cursor: pointer; min-height: 40px; min-width: 44px; padding: 0 12px; }
        .tl-slider__dots { align-items: center; display: flex; gap: 8px; }
        .tl-slider__dot { background: rgba(255, 255, 255, .55); border: 0; border-radius: 50%; cursor: pointer; height: 9px; padding: 0; width: 9px; }
        .tl-slider__dot[aria-pressed="true"] { background: #fff; transform: scale(1.25); }
        .tl-section { padding: 82px 0; }
        .tl-section--white { background: var(--white); }
        .tl-section__heading { align-items: end; display: flex; gap: 20px; justify-content: space-between; margin-bottom: 30px; }
        .tl-section__heading h2 { font: 400 38px/1.1 var(--serif); margin: 0; }
        .tl-section__heading p { color: var(--muted); margin: 8px 0 0; }
        .tl-text-link { color: var(--rust); font-size: 14px; font-weight: 700; text-decoration-thickness: 1px; text-underline-offset: 4px; white-space: nowrap; }
        .tl-categories { display: grid; gap: 12px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .tl-category { background: var(--sage); color: var(--ink); display: block; min-width: 0; overflow: hidden; text-decoration: none; }
        .tl-category__image { aspect-ratio: 1.5; overflow: hidden; }
        .tl-category__image img { height: 100%; object-fit: cover; transition: transform .35s ease; width: 100%; }
        .tl-category:hover .tl-category__image img { transform: scale(1.04); }
        .tl-category__label { align-items: center; display: flex; font: 400 20px/1.2 var(--serif); justify-content: space-between; padding: 14px 16px; }
        .tl-category__label span:last-child { color: var(--rust); font: 600 12px/1 var(--sans); }
        .tl-products { display: grid; gap: 12px; grid-template-columns: repeat(5, minmax(0, 1fr)); }
        .tl-product { min-width: 0; }
        .tl-product .store-product__image { aspect-ratio: .95; }
        .tl-product h3 { font-size: 12.5px; font-weight: 600; line-height: 1.35; margin: 10px 0 3px; }
        .tl-product h3 a { text-decoration: none; }
        .tl-product__price { align-items: baseline; color: var(--muted); display: flex; flex-wrap: wrap; font-size: 12px; gap: 4px; margin: 0; }
        .tl-product__price span:first-child { color: var(--ink); font-weight: 700; }
        .tl-product__price del { color: #8a928a; font-size: 10.5px; font-weight: 400; }
        .tl-product__badge, .store-product__sale-badge { font-size: 8px; height: 20px; min-width: 38px; padding: 0 6px; top: 8px; letter-spacing: .04em; }
        .tl-empty { background: var(--paper); color: var(--muted); grid-column: 1 / -1; padding: 34px; text-align: center; }
        .tl-promo-grid { display: grid; gap: 18px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .tl-promo { border: 1px solid var(--line); padding: 24px; }
        .tl-promo h3 { font: 400 23px/1.2 var(--serif); margin: 0 0 12px; }
        .tl-promo__value { color: var(--rust); font-size: 14px; font-weight: 700; }
        .tl-promo p { color: var(--muted); font-size: 14px; line-height: 1.55; }
        .tl-promo__code { align-items: center; border: 1px dashed #b5b7aa; display: flex; gap: 8px; justify-content: space-between; margin-top: 16px; padding: 8px 10px; }
        .tl-promo__code code { color: var(--ink); font-size: 13px; font-weight: 700; letter-spacing: 1px; }
        .tl-promo__code button { background: transparent; border: 0; color: var(--rust); cursor: pointer; font: 700 12px var(--sans); padding: 4px; }
        .tl-feature { background: var(--sage); padding: 54px 0; }
        .tl-feature__inner { align-items: center; display: flex; gap: 30px; justify-content: space-between; }
        .tl-feature h2 { font: 400 32px/1.15 var(--serif); margin: 0 0 9px; }
        .tl-feature p { color: #52685a; margin: 0; }
        .tl-promo-popup { align-items: center; background: rgba(15, 30, 21, .68); display: flex; inset: 0; justify-content: center; padding: 20px; position: fixed; z-index: 1000; }
        .tl-promo-popup[hidden] { display: none; }
        .tl-promo-popup__card { background: var(--white); border: 1px solid var(--line); box-shadow: 0 20px 70px rgba(0, 0, 0, .28); max-width: 480px; padding: 42px 34px 34px; position: relative; text-align: center; width: 100%; }
        .tl-promo-popup__close { align-items: center; background: transparent; border: 0; color: var(--ink); cursor: pointer; display: flex; font: 400 28px/1 var(--sans); height: 40px; justify-content: center; padding: 0; position: absolute; right: 12px; top: 10px; width: 40px; }
        .tl-promo-popup__close:hover { color: var(--rust); }
        .tl-promo-popup__eyebrow { color: var(--rust); font-size: 12px; font-weight: 700; letter-spacing: 1.5px; margin: 0 0 12px; text-transform: uppercase; }
        .tl-promo-popup h2 { font: 400 34px/1.12 var(--serif); margin: 0 0 14px; }
        .tl-promo-popup__description { color: var(--muted); line-height: 1.6; margin: 0 auto 16px; }
        .tl-promo-popup__value { color: var(--rust); font-size: 20px; font-weight: 700; margin: 0 0 18px; }
        .tl-promo-popup__code { border: 1px dashed #b5b7aa; display: inline-block; font-size: 14px; font-weight: 700; letter-spacing: 1px; padding: 9px 14px; }
        @media (max-width: 1200px) {
            .tl-products { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }
        @media (max-width: 900px) {
            .tl-slider, .tl-slide { min-height: 540px; }
            .tl-slide h1, .tl-slide h2 { font-size: 52px; }
            .tl-products { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
        }
        @media (max-width: 640px) {
            .tl-wrap { padding-left: 18px; padding-right: 18px; }
            .tl-slider, .tl-slide { min-height: 510px; }
            .tl-slide__content { padding: 56px 18px 112px; }
            .tl-slide h1, .tl-slide h2 { font-size: 42px; }
            .tl-slide__copy { font-size: 15px; }
            .tl-slider__controls { bottom: 18px; gap: 8px; left: 18px; right: 18px; }
            .tl-slider__dots { margin: 0 auto; }
            .tl-slider__toggle { font-size: 12px; }
            .tl-products { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
            .tl-product h3 { font-size: 11.5px; }
            .tl-product__price { font-size: 11px; }
            .tl-section { padding: 58px 0; }
            .tl-section[aria-labelledby="tl-categories-title"] { padding-bottom: 32px; }
            .tl-section[aria-labelledby="tl-new-title"] { padding-top: 32px; }
            .tl-section__heading { align-items: start; flex-direction: column; gap: 12px; }
            .tl-section__heading h2 { font-size: 32px; }
            .tl-categories { display: flex; gap: 14px; overflow-x: auto; overscroll-behavior-x: contain; padding-bottom: 12px; scroll-snap-type: x mandatory; scrollbar-width: none; }
            .tl-categories::-webkit-scrollbar { display: none; }
            .tl-category { flex: 0 0 82%; scroll-snap-align: start; }
            .tl-promo-grid { grid-template-columns: 1fr; }
            .tl-category__image { aspect-ratio: 1.65; }
            .tl-category__label { font-size: 20px; padding: 14px 16px; }
            .tl-feature__inner { align-items: start; flex-direction: column; }
            .tl-feature h2 { font-size: 28px; }
            .tl-promo-popup__card { padding: 42px 22px 26px; }
            .tl-promo-popup h2 { font-size: 29px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    @include('partials.header')
    @foreach ($popupPromos as $popupPromo)
        <section class="tl-promo-popup" data-promo-popup="{{ $popupPromo->id }}" hidden role="dialog" aria-modal="true" aria-labelledby="promo-popup-title-{{ $popupPromo->id }}">
            <div class="tl-promo-popup__card">
                <button class="tl-promo-popup__close" type="button" data-close-promo-popup aria-label="Tutup popup promo">&times;</button>
                <p class="tl-promo-popup__eyebrow">Promo spesial</p>
                <h2 id="promo-popup-title-{{ $popupPromo->id }}">{{ $popupPromo->name }}</h2>
                @if ($popupPromo->description)
                    <p class="tl-promo-popup__description">{{ $popupPromo->description }}</p>
                @endif
                <p class="tl-promo-popup__value">Hemat {{ $popupPromo->formattedValue() }}</p>
                @if ($popupPromo->code)
                    <p class="tl-promo-popup__code">{{ $popupPromo->code }}</p>
                @endif
            </div>
        </section>
    @endforeach
    <main class="tl-home">
        @php
            $homeSlides = [
                [
                    'image' => asset('assets/img/bg/bg.png'),
                    'alt' => 'Pemandangan alami untuk inspirasi koleksi The Lodge Maribaya',
                    'eyebrow' => 'The Lodge Maribaya · Merchandise resmi',
                    'title' => 'Kenangan dari alam, dibawa pulang.',
                    'copy' => 'Jelajahi merchandise pilihan yang terinspirasi dari suasana The Lodge Maribaya.',
                    'action' => 'Jelajahi koleksi',
                    'url' => route('shop'),
                ],
                [
                    'image' => asset('assets/img/baju/baju1.png'),
                    'alt' => 'Koleksi apparel The Lodge Maribaya',
                    'eyebrow' => 'Bawa cerita perjalananmu',
                    'title' => 'Langkah baru, cerita baru.',
                    'copy' => 'Temukan apparel untuk menemani perjalanan dan momen di alam terbuka.',
                    'action' => 'Lihat apparel',
                    'url' => route('shop', ['q' => 'baju']),
                ],
                [
                    'image' => asset('assets/img/tumbler/17-IMG_9739.jpg'),
                    'alt' => 'Tumbler merchandise The Lodge Maribaya',
                    'eyebrow' => 'Teman setiap perjalanan',
                    'title' => 'Jaga momen tetap dekat.',
                    'copy' => 'Pilih merchandise favorit dan bawa suasana The Lodge ke mana pun kamu pergi.',
                    'action' => 'Temukan koleksi',
                    'url' => route('shop'),
                ],
            ];
        @endphp
        <section class="tl-slider" data-home-slider aria-label="Sorotan merchandise The Lodge Maribaya" aria-roledescription="carousel">
            @foreach ($homeSlides as $slide)
                <article class="tl-slide {{ $loop->first ? 'is-active' : '' }}" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} dari {{ count($homeSlides) }}" @if (!$loop->first) aria-hidden="true" @endif>
                    <img class="tl-slide__image" src="{{ $slide['image'] }}" alt="{{ $slide['alt'] }}" @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                    <div class="tl-slide__content">
                        <div class="tl-slide__content-inner">
                            <p class="tl-eyebrow">{{ $slide['eyebrow'] }}</p>
                            @if ($loop->first)
                                <h1>{{ $slide['title'] }}</h1>
                            @else
                                <h2>{{ $slide['title'] }}</h2>
                            @endif
                            <p class="tl-slide__copy">{{ $slide['copy'] }}</p>
                            <a class="tl-button" href="{{ $slide['url'] }}">{{ $slide['action'] }}</a>
                        </div>
                    </div>
                </article>
            @endforeach
            <div class="tl-slider__controls">
                <button class="tl-slider__arrow" type="button" data-slide-prev aria-label="Slide sebelumnya">&#8592;</button>
                <div class="tl-slider__dots" aria-label="Pilih slide">
                    @foreach ($homeSlides as $slide)
                        <button class="tl-slider__dot" type="button" data-slide-index="{{ $loop->index }}" aria-label="Tampilkan slide {{ $loop->iteration }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
                <button class="tl-slider__arrow" type="button" data-slide-next aria-label="Slide berikutnya">&#8594;</button>
            </div>
        </section>
        <section class="tl-section" aria-labelledby="tl-categories-title">
            <div class="tl-wrap">
                <div class="tl-section__heading">
                    <div>
                        <p class="tl-eyebrow">Temukan favoritmu</p>
                        <h2 id="tl-categories-title">Jelajahi koleksi</h2>
                    </div>
                    <a class="tl-text-link" href="{{ route('shop') }}">Semua produk <span aria-hidden="true">→</span></a>
                </div>
                <div class="tl-categories">
                    <a class="tl-category" href="{{ route('shop', ['q' => 'baju']) }}">
                        <div class="tl-category__image"><img src="{{ asset('assets/img/baju/baju3.png') }}" alt="Koleksi apparel" loading="lazy"></div>
                        <div class="tl-category__label"><span>Apparel</span><span aria-hidden="true">01</span></div>
                    </a>
                    <a class="tl-category" href="{{ route('shop', ['q' => 'tumbler']) }}">
                        <div class="tl-category__image"><img src="{{ asset('assets/img/tumbler/17-IMG_9739.jpg') }}" alt="Koleksi tumbler" loading="lazy"></div>
                        <div class="tl-category__label"><span>Tumbler</span><span aria-hidden="true">02</span></div>
                    </a>
                    <a class="tl-category" href="{{ route('shop', ['q' => 'aksesoris']) }}">
                        <div class="tl-category__image"><img src="{{ asset('assets/img/kaoskaki/20-IMG_9669.jpg') }}" alt="Koleksi kaos kaki" loading="lazy"></div>
                        <div class="tl-category__label"><span>Aksesoris</span><span aria-hidden="true">03</span></div>
                    </a>
                </div>
            </div>
        </section>

        <section class="tl-section tl-section--white" aria-labelledby="tl-new-title">
            <div class="tl-wrap">
                <div class="tl-section__heading">
                    <div>
                        <p class="tl-eyebrow">Pilihan terkini</p>
                        <h2 id="tl-new-title">Produk terbaru</h2>
                        <p>Temukan koleksi merchandise pilihan The Lodge Maribaya.</p>
                    </div>
                    <a class="tl-text-link" href="{{ route('new-arrivals') }}">Lihat semua <span aria-hidden="true">→</span></a>
                </div>
                <div class="tl-products">
                    @forelse ($newProducts as $product)
                        @php
                            $productImage = $product->image_path
                                ? asset('storage/' . $product->image_path)
                                : asset('assets/img/shop/product/product-1.jpg');
                        @endphp
                        <article class="tl-product">
                            <a class="tl-product__image store-product__image {{ $product->image_path_back ? 'has-back' : '' }}" href="{{ route('product-details', $product->slug) }}">
                                <img class="store-product__image-front" src="{{ $productImage }}" alt="{{ $product->name }}" loading="lazy">
                                @if ($product->image_path_back)
                                    <img class="store-product__image-back" src="{{ asset('storage/' . $product->image_path_back) }}" alt="{{ $product->name }} tampak belakang" loading="lazy">
                                @endif
                                <span class="store-product__hover-label" aria-hidden="true">Lihat produk</span>
                                @if ($product->created_at->gt(now()->subDays(7)))
                                    <span class="tl-product__badge">Baru</span>
                                @endif
                                @if ($product->compare_at_price)
                                    <span class="store-product__sale-badge">Sale</span>
                                @endif
                            </a>
                            <h3><a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a></h3>
                            <p class="tl-product__price">
                                <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @if ($product->compare_at_price)
                                    <del>Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</del>
                                @endif
                            </p>
                        </article>
                    @empty
                        <p class="tl-empty">Produk akan segera hadir. Silakan jelajahi kembali nanti.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="tl-section" aria-labelledby="tl-picks-title">
            <div class="tl-wrap">
                <div class="tl-section__heading">
                    <div>
                        <p class="tl-eyebrow">Explore the collection</p>
                        <h2 id="tl-picks-title">Koleksi pilihan</h2>
                        <p>Temukan lebih banyak merchandise untuk dibawa pulang.</p>
                    </div>
                    <a class="tl-text-link" href="{{ route('shop') }}">Belanja semua <span aria-hidden="true">→</span></a>
                </div>
                <div class="tl-products">
                    @forelse ($saleProducts as $product)
                        @php
                            $productImage = $product->image_path
                                ? asset('storage/' . $product->image_path)
                                : asset('assets/img/shop/product/product-1.jpg');
                        @endphp
                        <article class="tl-product">
                            <a class="tl-product__image store-product__image {{ $product->image_path_back ? 'has-back' : '' }}" href="{{ route('product-details', $product->slug) }}">
                                <img class="store-product__image-front" src="{{ $productImage }}" alt="{{ $product->name }}" loading="lazy">
                                @if ($product->image_path_back)
                                    <img class="store-product__image-back" src="{{ asset('storage/' . $product->image_path_back) }}" alt="{{ $product->name }} tampak belakang" loading="lazy">
                                @endif
                                <span class="store-product__hover-label" aria-hidden="true">Lihat produk</span>
                                @if ($product->compare_at_price)
                                    <span class="store-product__sale-badge">Sale</span>
                                @endif
                            </a>
                            <h3><a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a></h3>
                            <p class="tl-product__price">
                                <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @if ($product->compare_at_price)
                                    <del>Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</del>
                                @endif
                            </p>
                        </article>
                    @empty
                        <p class="tl-empty">Koleksi akan segera hadir.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="tl-feature">
            <div class="tl-wrap tl-feature__inner">
                <div>
                    <h2>Siap menemukan kenangan berikutnya?</h2>
                    <p>Jelajahi koleksi merchandise The Lodge Maribaya.</p>
                </div>
                <a class="tl-button" href="{{ route('shop') }}">Belanja sekarang</a>
            </div>
        </section>
    </main>
    @include('partials.footer')
    <script>
        const homeSlider = document.querySelector('[data-home-slider]');

        if (homeSlider) {
            const slides = Array.from(homeSlider.querySelectorAll('.tl-slide'));
            const dots = Array.from(homeSlider.querySelectorAll('[data-slide-index]'));
            const pauseButton = homeSlider.querySelector('[data-slide-toggle]');
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            let activeSlide = 0;
            let autoplayTimer = null;
            let userPaused = reducedMotion.matches;
            let pointerInside = false;
            let focusInside = false;

            const stopAutoplay = () => {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            };

            const startAutoplay = () => {
                if (userPaused || pointerInside || focusInside || document.hidden || autoplayTimer) return;
                autoplayTimer = window.setInterval(() => showSlide(activeSlide + 1), 6000);
            };

            const showSlide = (slideIndex) => {
                activeSlide = (slideIndex + slides.length) % slides.length;
                slides.forEach((slide, index) => {
                    const isActive = index === activeSlide;
                    slide.classList.toggle('is-active', isActive);
                    slide.setAttribute('aria-hidden', String(!isActive));
                    dots[index].setAttribute('aria-pressed', String(isActive));
                });
            };

            const updatePauseButton = () => {
                pauseButton.setAttribute('aria-pressed', String(userPaused));
                pauseButton.setAttribute('aria-label', userPaused ? 'Putar slide otomatis' : 'Jeda slide otomatis');
                pauseButton.textContent = userPaused ? 'Putar' : 'Jeda';
            };

            homeSlider.querySelector('[data-slide-prev]').addEventListener('click', () => showSlide(activeSlide - 1));
            homeSlider.querySelector('[data-slide-next]').addEventListener('click', () => showSlide(activeSlide + 1));
            dots.forEach((dot, index) => dot.addEventListener('click', () => showSlide(index)));
            pauseButton.addEventListener('click', () => {
                userPaused = !userPaused;
                updatePauseButton();
                stopAutoplay();
                startAutoplay();
            });
            homeSlider.addEventListener('mouseenter', () => { pointerInside = true; stopAutoplay(); });
            homeSlider.addEventListener('mouseleave', () => { pointerInside = false; startAutoplay(); });
            homeSlider.addEventListener('focusin', () => { focusInside = true; stopAutoplay(); });
            homeSlider.addEventListener('focusout', (event) => {
                if (!homeSlider.contains(event.relatedTarget)) {
                    focusInside = false;
                    startAutoplay();
                }
            });
            homeSlider.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowLeft') showSlide(activeSlide - 1);
                if (event.key === 'ArrowRight') showSlide(activeSlide + 1);
            });
            document.addEventListener('visibilitychange', () => document.hidden ? stopAutoplay() : startAutoplay());
            reducedMotion.addEventListener('change', (event) => {
                if (event.matches) {
                    userPaused = true;
                    stopAutoplay();
                    updatePauseButton();
                }
            });

            updatePauseButton();
            startAutoplay();
        }

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

        const promoPopups = Array.from(document.querySelectorAll('[data-promo-popup]'));
        const nextPromoPopup = promoPopups.find((popup) => (
            window.localStorage.getItem(`merchtlm:closed-promo-popup:${popup.dataset.promoPopup}`) !== '1'
        ));

        if (nextPromoPopup) {
            nextPromoPopup.hidden = false;
            nextPromoPopup.querySelector('[data-close-promo-popup]').focus();
            nextPromoPopup.querySelector('[data-close-promo-popup]').addEventListener('click', () => {
                nextPromoPopup.hidden = true;
                window.localStorage.setItem(`merchtlm:closed-promo-popup:${nextPromoPopup.dataset.promoPopup}`, '1');
            });
        }
    </script>
</body>
</html>
