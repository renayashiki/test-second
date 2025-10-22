@extends('layouts.app')

@section('title', 'Register')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">

<div class="auth-page-container register-page">
    <h2 class="page-title">Register</h2>
    
    <div class="auth-card">
        <form method="POST" action="{{ route('register') }}" class="form-body">
            @csrf

            <div class="form-group">
                <div class="input-wrapper">
                    <label for="name" class="input-label">お名前</label>
                    <input id="name" type="text" name="name" placeholder="例: 山田 太郎" value="{{ old('name') }}" required autofocus>
                </div>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <label for="email" class="input-label">メールアドレス</label>
                    <input id="email" type="email" name="email" placeholder="例: test@example.com" value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <label for="password" class="input-label">パスワード</label>
                    <input id="password" type="password" name="password" placeholder="例: coachtech" required autocomplete="new-password">
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-action">
                <button type="submit" class="auth-btn">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
