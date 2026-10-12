<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Akun Saya | The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        .account-page { padding: 34px 0 72px; }
        .account-page__top { align-items: start; display: flex; flex-wrap: wrap; gap: 18px; justify-content: space-between; margin-bottom: 28px; }
        .account-page h1, .account-page h2 { font-family: var(--store-serif); font-weight: 400; }
        .account-page h1 { font-size: 38px; margin: 0 0 8px; }
        .account-page h2 { font-size: 28px; margin: 0 0 18px; }
        .account-page__muted { color: var(--store-muted); margin: 0; }
        .account-page__section { background: var(--store-white); border: 1px solid var(--store-line); margin-top: 22px; padding: 24px; }
        .account-order { border-top: 1px solid var(--store-line); padding: 18px 0; }
        .account-order:first-of-type { border-top: 0; }
        .account-order__top { align-items: start; display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; }
        .account-order__number { color: var(--store-ink); font-weight: 700; }
        .account-order__items { color: var(--store-muted); font-size: 13px; margin: 10px 0 0; }
        .account-page__empty { color: var(--store-muted); padding: 14px 0; }
        @media (max-width: 600px) { .account-page { padding-top: 26px; } .account-page h1 { font-size: 32px; } .account-page__section { padding: 18px; } }
    </style>
</head>
<body>
    @include('partials.header')
    <main class="store-page">
        <div class="store-wrap account-page">
            <div class="account-page__top">
                <div>
                    <p class="store-eyebrow">Akun pelanggan</p>
                    <h1>Halo, {{ $customer->name }}</h1>
                    <p class="account-page__muted">{{ $customer->email }} · {{ $customer->phone }}</p>
                </div>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button class="store-button store-button--outline" type="submit">Keluar</button>
                </form>
            </div>

            <section class="account-page__section" aria-labelledby="account-orders-title">
                <h2 id="account-orders-title">Riwayat pesanan</h2>
                @forelse ($orders as $order)
                    <article class="account-order">
                        <div class="account-order__top">
                            <div>
                                <div class="account-order__number">{{ $order->order_number }}</div>
                                <p class="account-page__muted">{{ $order->created_at->format('d M Y') }} · {{ \App\Models\Order::fulfillmentStatusLabel($order->fulfillment_status) }}</p>
                            </div>
                            <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                        </div>
                        <p class="account-order__items">{{ $order->items->map(fn ($item) => $item->product_name.' × '.$item->qty)->join(', ') }}</p>
                    </article>
                @empty
                    <p class="account-page__empty">Belum ada pesanan yang terhubung ke akun ini.</p>
                    <a class="store-button" href="{{ route('shop') }}">Jelajahi produk</a>
                @endforelse
                @if ($orders->hasPages())
                    <div class="store-pagination">{{ $orders->links('pagination::bootstrap-5') }}</div>
                @endif
            </section>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>