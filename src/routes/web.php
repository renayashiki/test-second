<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Laravel\Fortify\Features;
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




// Fortify 認証関連ルート
Route::group(['middleware' => 'web'], function () {

  // 🚨 修正: ログインビュー（GET /login） - ゲストミドルウェアを外すことでループを阻止
  Route::get('/login', \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class . '@create')
    ->name('login');

  // 登録ビュー（GET /register） - ゲストミドルウェアを外すことでループを阻止
  if (Features::enabled(Features::registration())) {
    Route::get('/register', \Laravel\Fortify\Http\Controllers\RegisteredUserController::class . '@create')
      ->name('register');
  }

  // ログイン処理（POST /login） - カスタムコントローラーを使用
  Route::post('/login', [AuthController::class, 'login'])->name('login.post');

  // ログアウト処理（POST /logout）
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

  // 登録処理（POST /register） - Fortifyのデフォルトを使用
  if (Features::enabled(Features::registration())) {
    Route::post('/register', \Laravel\Fortify\Http\Controllers\RegisteredUserController::class . '@store');
  }
});


// 管理画面 (ログイン必須)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
  // URL: /admin (ダッシュボード表示と検索処理)
  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

  // URL: /admin/export (CSVエクスポート処理)
  Route::get('/export', [DashboardController::class, 'export'])->name('export');

  // URL: /admin/{id} (削除処理。PUT/DELETEを使うのが一般的ですが、今回は簡単のためPOST/GETで対応)
  Route::post('/delete/{id}', [DashboardController::class, 'delete'])->name('delete');
});
