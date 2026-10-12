<style>
    .tl-store-header { background: #fff; border-bottom: 1px solid #e4e1d8; }
    .tl-store-header__inner { align-items: center; display: flex; gap: 28px; justify-content: space-between; min-height: 82px; }
    .tl-store-header__logo { flex: 0 0 auto; }
    .tl-store-header__logo img { display: block; height: auto; max-height: 52px; max-width: 210px; width: auto; }
    .tl-store-header__menu { display: contents; }
    .tl-store-header__nav { align-items: center; display: flex; gap: 24px; justify-content: center; }
    .tl-store-header__nav a, .tl-store-header__actions a { color: #262723; font-size: 13px; font-weight: 600; text-decoration: none; }
    .tl-store-header__nav a[aria-current="page"] { color: #873b32; }
    .tl-store-header__actions { align-items: center; display: flex; flex: 0 0 auto; gap: 14px; }
    .tl-store-header__actions a { white-space: nowrap; }
    .tl-store-header__search { display: flex; }
    .tl-store-header__search input { border: 1px solid #d8d5cc; border-radius: 3px 0 0 3px; min-width: 120px; padding: 8px 10px; }
    .tl-store-header__search button { background: #20211f; border: 1px solid #20211f; border-radius: 0 3px 3px 0; color: #fff; cursor: pointer; padding: 0 10px; }
    .tl-store-header__toggle { display: none; }
    .tl-store-header__mobile-actions { display: none; }
    @media (max-width: 900px) {
        .tl-store-header__inner { display: grid; gap: 8px; grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr); min-height: 64px; padding: 10px 0; position: relative; }
        .tl-store-header__logo { grid-column: 2; grid-row: 1; justify-self: center; margin: 0; }
        .tl-store-header__logo img { max-height: 42px; max-width: 150px; }
        .tl-store-header__toggle { align-items: center; background: #fff; border: 1px solid #d8d5cc; cursor: pointer; display: flex; flex: 0 0 42px; flex-direction: column; gap: 5px; grid-column: 1; grid-row: 1; height: 42px; justify-content: center; justify-self: start; padding: 0; width: 42px; }
        .tl-store-header__toggle span { background: #20211f; display: block; height: 2px; transition: transform .18s ease; width: 19px; }
        .tl-store-header__toggle[aria-expanded="true"] span:first-child { transform: translateY(3.5px) rotate(45deg); }
        .tl-store-header__toggle[aria-expanded="true"] span:last-child { transform: translateY(-3.5px) rotate(-45deg); }
        .tl-store-header__mobile-actions { align-items: center; display: flex; gap: 8px; grid-column: 3; grid-row: 1; justify-content: end; justify-self: end; }
        .tl-store-header__icon-button { align-items: center; background: #fff; border: 1px solid #d8d5cc; color: #20211f; display: inline-flex; height: 42px; justify-content: center; padding: 0; text-decoration: none; width: 37px; }
        .tl-store-header__search-icon { border: 1.5px solid currentColor; border-radius: 50%; height: 13px; position: relative; width: 13px; }
        .tl-store-header__search-icon::after { background: currentColor; content: ''; height: 6px; position: absolute; right: -4px; top: 10px; transform: rotate(-45deg); width: 1.5px; }
        .tl-store-header__bag-icon { border: 1.5px solid currentColor; border-radius: 1px; height: 13px; margin-top: 4px; position: relative; width: 14px; }
        .tl-store-header__bag-icon::before { border: 1.5px solid currentColor; border-bottom: 0; border-radius: 7px 7px 0 0; content: ''; height: 6px; left: 3px; position: absolute; top: -6px; width: 5px; }
        .tl-store-header__menu { background: #fff; border: 1px solid #e4e1d8; box-shadow: 0 12px 28px rgba(24, 28, 24, .12); display: none; flex-direction: column; gap: 0; grid-column: 1 / -1; grid-row: 2; left: 0; padding: 8px 18px 18px; position: absolute; right: 0; top: 100%; z-index: 30; }
        .tl-store-header__menu.is-open { display: flex; }
        .tl-store-header__nav { align-items: stretch; flex-direction: column; gap: 0; width: 100%; }
        .tl-store-header__nav a { border-bottom: 1px solid #eeece6; padding: 14px 2px; }
        .tl-store-header__actions { align-items: center; flex-wrap: wrap; gap: 14px 20px; padding-top: 16px; }
        .tl-store-header__search { flex: 1 0 100%; }
        .tl-store-header__search input { flex: 1; min-width: 0; width: auto; }
    }
</style>
<header class="tl-store-header">
    <div class="container">
        <div class="tl-store-header__inner">
            <a class="tl-store-header__logo" href="{{ route('home') }}" aria-label="The Lodge Maribaya, beranda">
                <img src="{{ asset('assets/img/logo/logothelodge.png') }}" alt="The Lodge Maribaya">
            </a>
            <button class="tl-store-header__toggle" type="button" data-mobile-menu-toggle aria-controls="storefront-menu" aria-expanded="false" aria-label="Buka menu">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </button>
            <div class="tl-store-header__mobile-actions">
                <button class="tl-store-header__icon-button" type="button" data-mobile-search-toggle aria-controls="storefront-menu" aria-expanded="false" aria-label="Buka pencarian" title="Cari produk">
                    <span class="tl-store-header__search-icon" aria-hidden="true"></span>
                </button>
                <a class="tl-store-header__icon-button" href="{{ route('cart') }}" aria-label="Keranjang" title="Keranjang">
                    <span class="tl-store-header__bag-icon" aria-hidden="true"></span>
                </a>
            </div>
            <div class="tl-store-header__menu" id="storefront-menu">
                <nav class="tl-store-header__nav" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Home</a>
                    <a href="{{ route('shop') }}" @if (request()->routeIs('shop')) aria-current="page" @endif>All Products</a>
                    <a href="{{ route('new-arrivals') }}" @if (request()->routeIs('new-arrivals')) aria-current="page" @endif>New Arrivals</a>
                </nav>
                <div class="tl-store-header__actions">
                    <form class="tl-store-header__search" action="{{ route('shop') }}" method="GET" role="search">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk" aria-label="Cari produk">
                        <button type="submit">Cari</button>
                    </form>
                    <a href="{{ route('cart') }}">Keranjang</a>
                    <a href="{{ route('account') }}">Account</a>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
    const mobileSearchButton = document.querySelector('[data-mobile-search-toggle]');
    const mobileMenu = document.getElementById('storefront-menu');
    const mobileHeader = mobileMenuButton?.closest('.tl-store-header');

    if (mobileMenuButton && mobileMenu && mobileHeader) {
        const closeMobileMenu = () => {
            mobileMenu.classList.remove('is-open');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            mobileMenuButton.setAttribute('aria-label', 'Buka menu');
            mobileSearchButton?.setAttribute('aria-expanded', 'false');
            mobileSearchButton?.setAttribute('aria-label', 'Buka pencarian');
        };

        mobileMenuButton.addEventListener('click', () => {
            const isOpen = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            mobileMenu.classList.toggle('is-open', !isOpen);
            mobileMenuButton.setAttribute('aria-expanded', String(!isOpen));
            mobileMenuButton.setAttribute('aria-label', isOpen ? 'Buka menu' : 'Tutup menu');
        });

        mobileSearchButton?.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('is-open');
            mobileMenu.classList.toggle('is-open', !isOpen);
            mobileMenuButton.setAttribute('aria-expanded', String(!isOpen));
            mobileMenuButton.setAttribute('aria-label', isOpen ? 'Buka menu' : 'Tutup menu');
            mobileSearchButton.setAttribute('aria-expanded', String(!isOpen));
            mobileSearchButton.setAttribute('aria-label', isOpen ? 'Buka pencarian' : 'Tutup pencarian');
            if (!isOpen) mobileMenu.querySelector('input[type="search"]')?.focus();
        });

        mobileMenu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMobileMenu));
        document.addEventListener('click', (event) => {
            if (!mobileHeader.contains(event.target)) closeMobileMenu();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeMobileMenu();
                mobileMenuButton.focus();
            }
        });
        window.matchMedia('(min-width: 901px)').addEventListener('change', closeMobileMenu);
    }
</script>