<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 1. お問い合わせフォームの入力画面
Route::get('/', [ContactController::class, 'index'])->name('contact.index');


// 2. 確認画面へのデータ送信と表示 (URL: /confirm)
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
//  ★★★ 修正ボタンの処理（セッションからデータを復元し、入力画面へリダイレクト）を追記 ★★★
// confirm.blade.phpからGETでアクセスされることを想定
Route::get('/back', [ContactController::class, 'back'])->name('contact.back');

// 3. データ保存と完了画面への遷移 (URL: /thanks)
// 完了画面の表示ルートと送信処理のルートを分け、完了画面へのURLもシンプルにします。
Route::post('/thanks', [ContactController::class, 'send'])->name('contact.send');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');




// ログインのPOSTのみカスタムコントローラーに切り替え
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Fortifyのデフォルトを使用: /register (GET/POST), /login (GET)

// ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 管理画面
// prefix('admin')とRoute::get('/')の組み合わせでURLが /admin となります
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
  // ...
});