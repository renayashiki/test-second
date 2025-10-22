<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // 認証処理を実行
        $request->authenticate();

        // セッションを再生成
        $request->session()->regenerate();

        // 🚨 修正: 認証が通ったら /admin へリダイレクト
        return redirect()->intended(RouteServiceProvider::ADMIN);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // ログアウト後は RouteServiceProvider::HOME (つまり /login) へリダイレクト
        return redirect(RouteServiceProvider::HOME);
    }
}
