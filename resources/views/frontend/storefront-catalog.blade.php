<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Jelajahi koleksi merchandise resmi The Lodge Maribaya.">
    <title>{{ $pageTitle ?? 'Semua produk' }} | The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
</head>
<body>
    @include('partials.header')
    <main class="store-page">
        <div class="store-wrap">
            <header class="store-heading">
                <p class="store-eyebrow">Official merchandise</p>
                <h1>{{ $pageTitle ?? 'Semua produk' }}</h1>
                <p>Temukan merchandise pilihan The Lodge Maribaya.</p>
            </header>

            <div class="store-heading">
                <p>{{ $products->total() }} produk ditemukan</p>
            </div>
            <div class="store-products">
                @forelse ($products as $product)
                    @php
                        $productImage = $product->image_path
                            ? asset('storage/' . $product->image_path)
                            : asset('assets/img/shop/product/product-1.jpg');
                    @endphp
                    <article class="store-product">
                        <a class="store-product__image {{ $product->image_path_back ? 'has-back' : '' }}" href="{{ route('product-details', $product->slug) }}">
                            <img class="store-product__image-front" src="{{ $productImage }}" alt="{{ $product->name }}" loading="lazy">
                            @if ($product->image_path_back)
                                <img class="store-product__image-back" src="{{ asset('storage/' . $product->image_path_back) }}" alt="{{ $product->name }} tampak belakang" loading="lazy">
                            @endif
                            <span class="store-product__hover-label" aria-hidden="true">Lihat produk</span>
                            @if ($product->created_at->gt(now()->subDays(7)))
                                <span class="store-product__badge">Baru</span>
                            @endif
                            @if ($product->compare_at_price)
                                <span class="store-product__sale-badge">Sale</span>
                            @endif
                        </a>
                        <h2><a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a></h2>
                        <p class="store-product__price">
                            <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @if ($product->compare_at_price)
                                <del>Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</del>
                            @endif
                        </p>
                    </article>
                @empty
                    <div class="store-empty">
                        <p>Belum ada produk yang tersedia saat ini.</p>
                        @if (request()->filled('q') || request()->filled('category'))
                            <a class="store-button store-button--outline" href="{{ route('shop') }}">Hapus filter</a>
                        @endif
                    </div>
                @endforelse
                @if ($products->hasPages())
                    <div class="store-pagination">{{ $products->withQueryString()->links('pagination::bootstrap-5') }}</div>
                @endif
            </div>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>
