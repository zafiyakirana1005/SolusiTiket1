<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Masuk atau buat akun pelanggan The Lodge Maribaya.">
    <title>{{ $mode === 'register' ? 'Daftar' : 'Masuk' }} | The Lodge Maribaya</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/storefront.css') }}">
    <style>
        .account-auth { margin: 0 auto; max-width: 520px; padding: 52px 18px 76px; }
        .account-auth__panel { background: var(--store-white); border: 1px solid var(--store-line); padding: 28px; }
        .account-auth h1 { font: 400 36px/1.15 var(--store-serif); margin: 0 0 8px; }
        .account-auth__intro { color: var(--store-muted); margin: 0 0 22px; }
        .account-auth__tabs { border-bottom: 1px solid var(--store-line); display: flex; gap: 22px; margin-bottom: 20px; }
        .account-auth__tabs a { border-bottom: 2px solid transparent; color: var(--store-muted); padding: 0 0 10px; text-decoration: none; }
        .account-auth__tabs a[aria-current="page"] { border-color: var(--store-ink); color: var(--store-ink); font-weight: 700; }
        .account-auth label { display: block; font-size: 13px; font-weight: 700; margin: 15px 0 6px; }
        .account-auth input { background: var(--store-white); border: 1px solid #c4d0c1; border-radius: 2px; color: var(--store-ink); min-height: 44px; padding: 10px; width: 100%; }
        .account-auth__error { background: #fbefec; border-left: 3px solid var(--store-rust); color: #8b3024; margin: 0 0 16px; padding: 11px 13px; }
        .account-auth .store-button { margin-top: 22px; width: 100%; }
        @media (max-width: 560px) { .account-auth { padding-top: 32px; } .account-auth__panel { padding: 22px 18px; } .account-auth h1 { font-size: 32px; } }
    </style>
</head>
<body>
    @include('partials.header')
    <main class="store-page">
        <section class="account-auth">
            <div class="account-auth__panel">
                <p class="store-eyebrow">Akun pelanggan</p>
                <h1>{{ $mode === 'register' ? 'Buat akun' : 'Selamat datang kembali' }}</h1>
                <p class="account-auth__intro">{{ $mode === 'register' ? 'Daftar untuk melihat pesanan dari akunmu.' : 'Masuk untuk melihat akun dan pesananmu.' }}</p>
                <nav class="account-auth__tabs" aria-label="Akun pelanggan">
                    <a href="{{ route('login') }}" @if ($mode === 'login') aria-current="page" @endif>Masuk</a>
                    <a href="{{ route('register') }}" @if ($mode === 'register') aria-current="page" @endif>Daftar</a>
                </nav>
                @if ($errors->any())
                    <div class="account-auth__error" role="alert">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ $mode === 'register' ? route('register.store') : route('login.store') }}">
                    @csrf
                    @if ($mode === 'register')
                        <label for="name">Nama lengkap</label>
                        <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="150" required>
                        <label for="phone">Nomor HP</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" maxlength="20" required>
                    @endif
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="150" required>
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="{{ $mode === 'register' ? 'new-password' : 'current-password' }}" @if ($mode === 'register') minlength="12" @endif required>
                    @if ($mode === 'register')
                        <label for="password_confirmation">Ulangi password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="12" required>
                    @endif
                    <button class="store-button" type="submit">{{ $mode === 'register' ? 'Daftar' : 'Masuk' }}</button>
                </form>
            </div>
        </section>
    </main>
    @include('partials.footer')
</body>
</html>