<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>FashionablyLate - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth/common.css') }}">
</head>
<body>
    <header class="app-header">
        <h1 class="header-logo">FashionablyLate</h1>
        <div class="header-nav">
            @if (Route::currentRouteName() == 'register')
                <a href="{{ route('login') }}" class="header-link">login</a>
            @elseif (Route::currentRouteName() == 'login')
                <a href="{{ route('register') }}" class="header-link">register</a>
            @endif
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>