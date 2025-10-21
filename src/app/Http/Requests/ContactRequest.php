<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        // 認証チェックをスキップする場合は true に設定
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'email' => 'required|email', // メールアドレス形式のチェックを追加
            // 電話番号の3つのフィールドが存在し、全てが数字であることを確認
            'tel1' => 'required|numeric',
            'tel2' => 'required|numeric',
            'tel3' => 'required|numeric',
            'address' => 'required',
            'building' => 'nullable', // 建物名は任意
            'category_id' => 'required',
            'content' => 'required|max:120', // 120文字以内のチェックを追加
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => '姓を入力してください',
            'last_name.required' => '名を入力してください',
            'gender.required' => '性別を選択してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください', // メール形式エラーメッセージ

            // 電話番号はどれか一つでも抜けている場合に、まとめて「電話番号を入力してください」と表示させるため、
            // 複数のフィールドで同じエラーメッセージを使います。
            'tel1.required' => '電話番号を入力してください',
            'tel2.required' => '電話番号を入力してください',
            'tel3.required' => '電話番号を入力してください',
            'tel1.numeric' => '電話番号を入力してください', // numericルールも追加
            'tel2.numeric' => '電話番号を入力してください',
            'tel3.numeric' => '電話番号を入力してください',

            'address.required' => '住所を入力してください',
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'content.required' => 'お問い合わせ内容を入力してください',
            'content.max' => 'お問合せ内容は120文字以内で入力してください', // 文字数エラーメッセージ
        ];
    }
}
