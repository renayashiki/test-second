@extends('layouts.app')

@section('title', 'Login')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">

<div class="auth-page-container login-page">
    <h2 class="page-title">Login</h2>
    
    <div class="auth-card">
        <form method="POST" action="{{ route('login.post') }}" class="form-body">
            @csrf

            <div class="form-group">
                <div class="input-wrapper">
                    <label for="email" class="input-label">メールアドレス</label>
                    <input id="email" type="email" name="email" placeholder="例: test@example.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <label for="password" class="input-label">パスワード</label>
                    <input id="password" type="password" name="password" placeholder="例: coachtech" required autocomplete="current-password">
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-action">
                <button type="submit" class="auth-btn">ログイン</button>
            </div>
        </form>
    </div>
</div>
@endsection