{{--
  Partial: resources/views/partials/product-card.blade.php
  Pakai: @include('partials.product-card', ['product' => $product])
  
  Efek hover:
  - Jika ada image_path_back → gambar depan fade-out, gambar belakang fade-in
  - Jika tidak ada image_path_back → hover normal (zoom sedikit)
--}}

@php
  $imgFront = $product->image_path
    ? asset('storage/' . $product->image_path)
    : asset('assets/img/shop/product/product-1.jpg');

  $imgBack = $product->image_path_back
    ? asset('storage/' . $product->image_path_back)
    : null;

  $isNew   = $product->created_at->gt(now()->subDays(7));
  $hasBack = !is_null($imgBack);
@endphp

<div class="product__wrapper tl-product-card mb-60" style="position:relative;">
  <div class="product__thumb" style="position:relative;overflow:hidden;">
    <a href="{{ route('product-details', $product->slug) }}" class="w-img" style="display:block;position:relative;">

      {{-- Gambar Depan --}}
      <img src="{{ $imgFront }}"
           alt="{{ $product->name }}"
           class="tl-img-front"
           style="width:100%;display:block;transition:opacity 0.4s ease,transform 0.4s ease;position:relative;z-index:1;">

      {{-- Gambar Belakang (hanya render kalau ada) --}}
      @if ($hasBack)
      <img src="{{ $imgBack }}"
           alt="{{ $product->name }} - belakang"
           class="tl-img-back"
           style="width:100%;display:block;transition:opacity 0.4s ease,transform 0.4s ease;position:absolute;top:0;left:0;opacity:0;z-index:2;">
      @endif
    </a>

    {{-- Action buttons --}}
    <div class="product__action transition-3" style="position:absolute;bottom:12px;right:12px;display:flex;flex-direction:column;gap:6px;z-index:10;">
      <a href="{{ route('wishlist') }}" data-bs-toggle="tooltip" title="Wishlist">
        <i class="fal fa-heart"></i>
      </a>
      <a href="{{ route('product-details', $product->slug) }}" data-bs-toggle="tooltip" title="Lihat Detail">
        <i class="fal fa-search"></i>
      </a>
    </div>

    {{-- Badge NEW --}}
    @if ($isNew)
    <div class="product__sale" style="position:absolute;top:0;left:12px;z-index:5;">
      <span class="new">new</span>
    </div>
    @endif
    @if ($product->compare_at_price)
    <div class="product__sale" style="position:absolute;top:0;right:12px;z-index:5;">
      <span class="new">sale</span>
    </div>
    @endif
  </div>

  {{-- Info produk --}}
  <div class="product__content p-relative">
    <div class="product__content-inner">
      <h4>
        <a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a>
      </h4>
      <div class="product__price transition-3">
        <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
        @if ($product->compare_at_price)
          <del class="text-muted small">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</del>
        @endif
      </div>
    </div>
    <div class="add-cart p-absolute transition-3">
      <a href="{{ route('product-details', $product->slug) }}">+ Lihat Produk</a>
    </div>
  </div>
</div>

{{-- CSS hover swap (inject sekali, idempotent) --}}
@once
<style>
/* ── Product card hover swap image ── */
.tl-product-card .tl-img-front  { transform: scale(1); }
.tl-product-card:hover .tl-img-front  { opacity: 0; transform: scale(1.03); }
.tl-product-card .tl-img-back   { transform: scale(1.03); }
.tl-product-card:hover .tl-img-back   { opacity: 1; transform: scale(1); }

/* Kalau tidak ada gambar belakang, cukup zoom halus */
.tl-product-card:not(:has(.tl-img-back)):hover .tl-img-front { opacity: 1; transform: scale(1.04); }

/* Action buttons muncul saat hover */
.tl-product-card .product__action { opacity: 0; visibility: hidden; transition: opacity .3s, visibility .3s; }
.tl-product-card:hover .product__action { opacity: 1; visibility: visible; }

/* Add to cart link muncul saat hover */
.tl-product-card .add-cart { opacity: 0; visibility: hidden; transition: opacity .3s, visibility .3s; }
.tl-product-card:hover .add-cart { opacity: 1; visibility: visible; }

/* Tinggi gambar seragam */
.tl-product-card .product__thumb img { height: 300px; object-fit: cover; }
</style>
@endonce
