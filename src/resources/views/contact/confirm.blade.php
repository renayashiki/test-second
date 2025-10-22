<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Confirm</title>
    <!-- CSSパスはasset()ヘルパーを使用 -->
    <link rel="stylesheet" href="{{ asset('css/contact/confirm.css') }}">
</head>
<body>
    <header class="header">
        <h1 class="logo">FashionablyLate</h1>
        <hr class="header-divider"> 
    </header>

    <div class="container">
        <h2 class="page-title">Confirm</h2>
        
        <form action="{{ route('contact.send') }}" method="POST" class="confirm-form">
            @csrf
            <div class="confirm-table">
                <!-- お名前 -->
                <div class="confirm-row">
                    <div class="confirm-label">お名前</div>
                    <div class="confirm-value">{{ $contact['first_name'] }} {{ $contact['last_name'] }}</div>
                    <!-- hidden: index.bladeのフィールド名と完全に一致させる -->
                    <input type="hidden" name="first_name" value="{{ $contact['first_name'] }}">
                    <input type="hidden" name="last_name" value="{{ $contact['last_name'] }}">
                </div>
                
                <!-- 性別 (修正対象: short-row クラスを追加し、縦幅を狭める) -->
                <div class="confirm-row short-row">
                    <div class="confirm-label">性別</div>
                    <div class="confirm-value">
                        {{ [
                            'male' => '男性', 
                            'female' => '女性', 
                            'other' => 'その他'
                        ][$contact['gender']] ?? '不明' }}
                    </div>
                    <!-- hidden: index.bladeのフィールド名と完全に一致させる -->
                    <input type="hidden" name="gender" value="{{ $contact['gender'] }}">
                </div>
                
                <!-- メールアドレス -->
                <div class="confirm-row">
                    <div class="confirm-label">メールアドレス</div>
                    <div class="confirm-value">{{ $contact['email'] }}</div>
                    <input type="hidden" name="email" value="{{ $contact['email'] }}">
                </div>
                
                <!-- 電話番号 -->
                <div class="confirm-row">
                    <div class="confirm-label">電話番号</div>
                    <div class="confirm-value">{{ $contact['tel1'] }}-{{ $contact['tel2'] }}-{{ $contact['tel3'] }}</div>
                    <!-- hidden: index.bladeのフィールド名と完全に一致させる (tel1, tel2, tel3) -->
                    <input type="hidden" name="tel1" value="{{ $contact['tel1'] }}">
                    <input type="hidden" name="tel2" value="{{ $contact['tel2'] }}">
                    <input type="hidden" name="tel3" value="{{ $contact['tel3'] }}">
                </div>
                
                <!-- 住所 -->
                <div class="confirm-row">
                    <div class="confirm-label">住所</div>
                    <div class="confirm-value">{{ $contact['address'] }}</div>
                    <input type="hidden" name="address" value="{{ $contact['address'] }}">
                </div>
                
                <!-- 建物名 -->
                <div class="confirm-row">
                    <div class="confirm-label">建物名</div>
                    <div class="confirm-value">{{ $contact['building'] ?? 'なし' }}</div>
                    <input type="hidden" name="building" value="{{ $contact['building'] }}">
                </div>
                
                <!-- お問い合わせの種類 (修正対象: short-row クラスを追加し、縦幅を狭める) -->
                <div class="confirm-row short-row">
                    <div class="confirm-label">お問い合わせの種類</div>
                    <div class="confirm-value">
                        @php
                            $categories = [
                                1 => '商品のお届けについて',
                                2 => '商品の交換について',
                                3 => '商品トラブル',
                                4 => 'ショップへのお問い合わせ',
                                5 => 'その他',
                            ];
                        @endphp
                        {{ $categories[$contact['category_id']] ?? '不明' }}
                    </div>
                    <!-- hidden: index.bladeのフィールド名と完全に一致させる -->
                    <input type="hidden" name="category_id" value="{{ $contact['category_id'] }}">
                </div>
                
                <!-- お問い合わせ内容 (修正対象: content-row クラスを維持し、縦幅を広げる) -->
                <div class="confirm-row content-row">
                    <div class="confirm-label">お問い合わせ内容</div>
                    <div class="confirm-value">{{ $contact['content'] }}</div>
                    <input type="hidden" name="content" value="{{ $contact['content'] }}">
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="submit-button">送信</button>
                <button type="submit" formaction="{{ route('contact.back') }}" formmethod="GET" class="back-button">修正</button>
            </div>
        </form>
    </div>
</body>
</html>
