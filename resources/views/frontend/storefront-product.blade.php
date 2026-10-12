<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ Str::limit(strip_tags($product->description ?: $product->name . ' merchandise resmi The Lodge Maribaya.'), 155) }}">
    <title>{{ $product->name }} | Merchandise The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
</head>
<body>
    @include('partials.header')
    @php
        $productImages = collect();

        if ($product->image_path) {
            $productImages->push([
                'src' => asset('storage/' . $product->image_path),
                'alt' => $product->name . ' tampak depan',
                'label' => 'Foto depan',
            ]);
        }

        if ($product->image_path_back) {
            $productImages->push([
                'src' => asset('storage/' . $product->image_path_back),
                'alt' => $product->name . ' tampak belakang',
                'label' => 'Foto belakang',
            ]);
        }

        if ($productImages->isEmpty()) {
            $productImages->push([
                'src' => asset('assets/img/shop/product/product-1.jpg'),
                'alt' => $product->name,
                'label' => 'Foto produk',
            ]);
        }
    @endphp
    <main class="store-page">
        <div class="store-wrap">
            <section class="store-details">
                <div class="store-gallery" data-product-gallery>
                    <button class="store-gallery__main" type="button" data-gallery-main aria-label="Perbesar {{ $productImages->first()['label'] }}" aria-pressed="false">
                        <img src="{{ $productImages->first()['src'] }}" alt="{{ $productImages->first()['alt'] }}" fetchpriority="high">
                    </button>
                    @if ($productImages->count() > 1)
                        <div class="store-gallery__thumbs" role="group" aria-label="Pilihan foto produk">
                            @foreach ($productImages as $image)
                                <button class="store-gallery__thumb {{ $loop->first ? 'is-active' : '' }}" type="button" data-gallery-thumb data-src="{{ $image['src'] }}" data-alt="{{ $image['alt'] }}" data-label="{{ $image['label'] }}" aria-label="Pilih {{ $image['label'] }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                    <img src="{{ $image['src'] }}" alt="" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="store-details__copy">
                    @if ($product->category)
                        <p class="store-eyebrow">{{ $product->category->name }}</p>
                    @endif
                    <h1>{{ $product->name }}</h1>
                    @if ($product->subtitle)
                        <p class="store-details__subtitle">{{ $product->subtitle }}</p>
                    @endif
                    <p class="store-details__price">
                        <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @if ($product->compare_at_price)
                            <del>Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</del>
                        @endif
                    </p>
                    @if ($product->description)
                        <p class="store-details__description">{{ $product->description }}</p>
                    @endif
                    <p class="store-details__stock">{{ $product->totalStock() > 0 ? 'Stok tersedia: ' . $product->totalStock() : 'Stok sedang habis' }}</p>
                    @php
                        $availableVariant = $product->variants->first(fn ($variant) => $variant->stock > 0);
                    @endphp
                    <form class="store-details__form" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        @if ($product->variants->isNotEmpty())
                            <fieldset class="store-size-picker">
                                <legend>Pilih ukuran</legend>
                                <div class="store-size-options">
                                    @foreach ($product->variants as $variant)
                                        <label class="store-size-option {{ $variant->stock < 1 ? 'is-disabled' : '' }}">
                                            <input type="radio" name="variant_id" value="{{ $variant->id }}" data-stock="{{ $variant->stock }}" @checked($availableVariant?->id === $variant->id) @disabled($variant->stock < 1) required>
                                            <span>{{ $variant->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif
                        <div class="store-details__form-row">
                            <div>
                                <label for="product-qty">Jumlah</label>
                                <div class="store-quantity" data-quantity-control>
                                    <button type="button" data-quantity-decrease aria-label="Kurangi jumlah" @disabled($product->totalStock() < 1)>-</button>
                                    <input class="store-qty" id="product-qty" type="number" name="qty" value="1" min="1" max="{{ max(1, $availableVariant?->stock ?? $product->totalStock()) }}" required>
                                    <button type="button" data-quantity-increase aria-label="Tambah jumlah" @disabled($product->totalStock() < 1)>+</button>
                                </div>
                            </div>
                            <button class="store-button" type="submit" @disabled($product->totalStock() < 1 || ($product->variants->isNotEmpty() && !$availableVariant))>{{ $product->totalStock() > 0 ? 'Tambah ke keranjang' : 'Stok habis' }}</button>
                        </div>
                    </form>
                    @if (session('success'))
                        <div class="store-cart-notice" data-cart-notice role="status" aria-live="polite">
                            <p>{{ session('success') }}</p>
                            <a href="{{ route('cart') }}">Lihat keranjang</a>
                            <button type="button" data-dismiss-cart-notice aria-label="Tutup notifikasi">&times;</button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <p role="alert">{{ $errors->first() }}</p>
                    @endif
                </div>
            </section>
        </div>
        @if ($related->isNotEmpty())
            <section class="store-related" aria-labelledby="related-title">
                <div class="store-wrap">
                    <h2 id="related-title">Produk serupa</h2>
                    <div class="store-products">
                        @foreach ($related as $relatedProduct)
                            @php
                                $relatedImage = $relatedProduct->image_path
                                    ? asset('storage/' . $relatedProduct->image_path)
                                    : asset('assets/img/shop/product/product-1.jpg');
                            @endphp
                            <article class="store-product">
                                <a class="store-product__image {{ $relatedProduct->image_path_back ? 'has-back' : '' }}" href="{{ route('product-details', $relatedProduct->slug) }}">
                                    <img class="store-product__image-front" src="{{ $relatedImage }}" alt="{{ $relatedProduct->name }}" loading="lazy">
                                    @if ($relatedProduct->image_path_back)
                                        <img class="store-product__image-back" src="{{ asset('storage/' . $relatedProduct->image_path_back) }}" alt="{{ $relatedProduct->name }} tampak belakang" loading="lazy">
                                    @endif
                                    <span class="store-product__hover-label" aria-hidden="true">Lihat produk</span>
                                    @if ($relatedProduct->compare_at_price)
                                        <span class="store-product__sale-badge">Sale</span>
                                    @endif
                                </a>
                                <h2><a href="{{ route('product-details', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a></h2>
                                <p class="store-product__price">
                                    <span>Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</span>
                                    @if ($relatedProduct->compare_at_price)
                                        <del>Rp {{ number_format($relatedProduct->compare_at_price, 0, ',', '.') }}</del>
                                    @endif
                                </p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>
    @include('partials.footer')
    <script>
        document.querySelectorAll('[data-product-gallery]').forEach((gallery) => {
            const mainButton = gallery.querySelector('[data-gallery-main]');
            const mainImage = mainButton.querySelector('img');

            gallery.querySelectorAll('[data-gallery-thumb]').forEach((thumbnail) => {
                thumbnail.addEventListener('click', () => {
                    mainImage.src = thumbnail.dataset.src;
                    mainImage.alt = thumbnail.dataset.alt;
                    mainButton.setAttribute('aria-label', `Perbesar ${thumbnail.dataset.label}`);
                    mainButton.setAttribute('aria-pressed', 'false');
                    mainButton.classList.remove('is-zoomed');
                    mainImage.style.transform = 'scale(1)';

                    gallery.querySelectorAll('[data-gallery-thumb]').forEach((otherThumbnail) => {
                        const isActive = otherThumbnail === thumbnail;
                        otherThumbnail.classList.toggle('is-active', isActive);
                        otherThumbnail.setAttribute('aria-pressed', String(isActive));
                    });
                });
            });

            mainButton.addEventListener('pointermove', (event) => {
                if (event.pointerType === 'touch') return;
                const bounds = mainButton.getBoundingClientRect();
                const x = ((event.clientX - bounds.left) / bounds.width) * 100;
                const y = ((event.clientY - bounds.top) / bounds.height) * 100;
                mainButton.style.setProperty('--zoom-x', `${x}%`);
                mainButton.style.setProperty('--zoom-y', `${y}%`);
                mainImage.style.transition = 'none';
                mainImage.style.transform = 'scale(1.8)';
            });

            mainButton.addEventListener('pointerleave', () => {
                mainButton.style.removeProperty('--zoom-x');
                mainButton.style.removeProperty('--zoom-y');
                if (!mainButton.classList.contains('is-zoomed')) {
                    mainImage.style.transform = 'scale(1)';
                }
            });

            mainButton.addEventListener('click', () => {
                const isZoomed = mainButton.classList.toggle('is-zoomed');
                mainImage.style.transition = 'none';
                mainImage.style.transform = isZoomed ? 'scale(1.8)' : 'scale(1)';
                mainButton.setAttribute('aria-pressed', String(isZoomed));
            });
        });

        document.querySelectorAll('[data-quantity-control]').forEach((control) => {
            const quantityInput = control.querySelector('input');
            const decreaseButton = control.querySelector('[data-quantity-decrease]');
            const increaseButton = control.querySelector('[data-quantity-increase]');
            const variantOptions = document.querySelectorAll('input[name="variant_id"]');

            const updateQuantityButtons = () => {
                const quantity = Number(quantityInput.value);
                decreaseButton.disabled = quantity <= Number(quantityInput.min);
                increaseButton.disabled = quantity >= Number(quantityInput.max);
            };

            decreaseButton.addEventListener('click', () => {
                quantityInput.stepDown();
                updateQuantityButtons();
            });

            increaseButton.addEventListener('click', () => {
                quantityInput.stepUp();
                updateQuantityButtons();
            });

            quantityInput.addEventListener('input', updateQuantityButtons);

            variantOptions.forEach((option) => {
                option.addEventListener('change', () => {
                    quantityInput.max = option.dataset.stock;
                    quantityInput.value = Math.min(Number(quantityInput.value), Number(option.dataset.stock));
                    updateQuantityButtons();
                });
            });

            updateQuantityButtons();
        });

        document.querySelectorAll('[data-dismiss-cart-notice]').forEach((button) => {
            button.addEventListener('click', () => button.closest('[data-cart-notice]')?.remove());
        });
    </script>
</body>
</html>
