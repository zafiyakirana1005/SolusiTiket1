<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Admin | The Lodge Maribaya</title>
    <style>
        * { box-sizing: border-box; }
        body { align-items: center; background: #f1f3e9; color: #20372d; display: flex; font-family: Arial, sans-serif; justify-content: center; margin: 0; min-height: 100vh; padding: 24px; }
        main { background: #fbfcf7; border: 1px solid #d9e0d2; max-width: 420px; padding: 34px; width: 100%; }
        h1 { font: 400 30px/1.15 Georgia, serif; margin: 0 0 8px; }
        p { color: #627166; font-size: 14px; margin: 0 0 24px; }
        label { display: block; font-size: 13px; font-weight: 700; margin: 16px 0 7px; }
        input { background: #fff; border: 1px solid #c4d0c1; border-radius: 2px; color: inherit; min-height: 44px; padding: 10px; width: 100%; }
        button { background: #20372d; border: 1px solid #20372d; border-radius: 2px; color: #fff; cursor: pointer; font-size: 14px; font-weight: 700; margin-top: 22px; min-height: 46px; width: 100%; }
        .error { color: #a33125; font-size: 13px; margin: 10px 0 0; }
    </style>
</head>
<body>
    <main>
        <h1>Masuk Admin</h1>
        <p>The Lodge Maribaya Backoffice</p>
        @if ($errors->any())
            <div class="error" role="alert">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>
            <button type="submit">Masuk</button>
        </form>
    </main>
</body>
</html>