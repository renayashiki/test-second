<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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

// お問い合わせフォームの入力画面
Route::get('/', [ContactController::class, 'index'])->name('contact.index');


// 【★このルートが不足していました】確認画面へのデータ送信と表示
Route::post('/contact/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');

// データ保存と完了画面への遷移
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// トップページ（http://localhost/）にアクセスしたときに、お問い合わせページを表示する場合
// Route::get('/', [ContactController::class, 'index'