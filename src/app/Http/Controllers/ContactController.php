<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    // ----------------------------------------------------
    // 1. 入力画面表示 (GET /)
    // ----------------------------------------------------
    public function index(Request $request)
    {
        

        // old()ヘルパがセッションとリダイレクトからデータを取得するため、
        // 実際にはビューに渡す必要はありませんが、明示的に記述する場合は$contactを使用します。
        return view('contact.index');
    }

    // ----------------------------------------------------
    // 2. 確認画面処理 (POST /confirm)
    // ----------------------------------------------------
    // ★ContactRequest::class を使用し、バリデーションを Form Request に任せる
    public function confirm(ContactRequest $request)
    {
        // ContactRequestでバリデーションに成功したデータのみを取得
        $contact = $request->validated();
        session(['contact_data' => $contact]);

        return view('contact.confirm', compact('contact'));

        // 氏名と電話番号を結合して確認画面用に整形
        $contact['name'] = $contact['first_name'] . ' ' . $contact['last_name'];
        $contact['tel'] = $contact['tel1'] . $contact['tel2'] . $contact['tel3'];

        // 修正ボタンで戻った際に値を保持するため、全データをセッションに一時保存する
        $request->session()->put('contact_data', $contact);

        return view('contact.confirm', compact('contact'));
    }

    // ----------------------------------------------------
    // 3. 送信処理 (POST /thanks)
    // ----------------------------------------------------
    public function send(Request $request)
    {
        // セッションから最終的な送信データを取り出す

        $contact = $request->session()->get('contact_data');
        
        if (!$contact) {
            // セッション切れなどでデータがない場合は入力画面に戻す
            return redirect()->route('contact.index');
        }

        // ★本来、この部分でデータベース保存（Contact::create($contact)など）や
        // ★メール送信（Mail::to()->send()など）のロジックが入ります。

        // データベース処理後、セッションデータをクリア
        $request->session()->forget('contact_data');

        // 完了画面へリダイレクト (二重送信防止のためPOSTの後にGETリダイレクトが望ましい)
        return redirect()->route('contact.thanks'); 
    }
    public function back(Request $request)
    {
        // セッションから一時保存したデータを取り出す
        $contact = $request->session()->get('contact_data');

        // セッションデータがない場合は入力画面へリダイレクト
        if (!$contact) {
            return redirect()->route('contact.index');
        }

        // セッションデータを withInput() にセットし、入力画面に戻る
        // withInput($contact) を使用することで、index.blade.phpでold()ヘルパーが使えるようになる
        return redirect()->route('contact.index')->withInput($contact);
    }
    // ----------------------------------------------------
    // 4. 完了画面表示 (GET /thanks)
    // ----------------------------------------------------
    public function thanks()
    {
        return view('contact.thanks');
    }
}
