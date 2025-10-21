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

// 1. お問い合わせフォームの入力画面
Route::get('/', [ContactController::class, 'index'])->name('contact.index');


// 2. 確認画面へのデータ送信と表示 (URL: /confirm)
Route::post('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');

// 3. データ保存と完了画面への遷移 (URL: /thanks)
// 完了画面の表示ルートと送信処理のルートを分け、完了画面へのURLもシンプルにします。
Route::post('/thanks', [ContactController::class, 'send'])->name('contact.send');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');
