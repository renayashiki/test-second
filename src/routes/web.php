<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController; // 💡 修正: Admin\DashboardController を削除
use App\Http\Controllers\AuthController;
use Laravel\Fortify\Features;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// お問い合わせフォーム関連（一般ユーザー向け）
Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::get('/back', [ContactController::class, 'back'])->name('contact.back');
Route::post('/thanks', [ContactController::class, 'send'])->name('contact.send');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');


// --- 認証関連ルート (Fortifyを使用しつつカスタム) ---
Route::group(['middleware' => 'web'], function () {

  // ログインビュー (GET /login)
  // 💡 修正: AdminLoginController が存在しないため、Fortifyのデフォルトビューコントローラーを使用
  Route::get('/login', \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class . '@create')->name('login');

  // 登録ビュー (GET /register)
  if (Features::enabled(Features::registration())) {
    Route::get('/register', \Laravel\Fortify\Http\Controllers\RegisteredUserController::class . '@create')
      ->name('register');
  }

  // ログイン処理 (POST /login) - カスタムコントローラーを使用
  Route::post('/login', [AuthController::class, 'login'])->name('login.post');

  // 登録処理 (POST /register) - Fortifyのデフォルトを使用
  if (Features::enabled(Features::registration())) {
    Route::post('/register', \Laravel\Fortify\Http\Controllers\RegisteredUserController::class . '@store');
  }

  // 注意: このセクションにはログアウトルートは定義しない
});


// --- 管理者画面ルート (ログイン必須) ---
Route::prefix('admin')->name('admin.')->group(function () {

  // ログアウトは認証ミドルウェアの外に置き、名前を admin.logout に確実に設定
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // ルート名: admin.logout

  // ログイン必須の管理画面ルート (authミドルウェアが必要)
  Route::middleware(['auth'])->group(function () {
    // URL: /admin, ルート名: admin.dashboard
    // 💡 修正: App\Http\Controllers\DashboardController を直接参照
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // URL: /admin/export, ルート名: admin.export
    Route::get('/export', [DashboardController::class, 'export'])->name('export');

    // URL: /admin/contact/{id}, ルート名: admin.delete
    Route::delete('/contact/{id}', [DashboardController::class, 'delete'])->name('delete');
  });
});
