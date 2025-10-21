<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Confirm</title>
    <link rel="stylesheet" href="/css/contact/confirm.css">
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
                <div class="confirm-row">
                    <div class="confirm-label">お名前</div>
                    <div class="confirm-value">山田 太郎</div>
                    <input type="hidden" name="name" value="山田 太郎">
                </div>
                
                <div class="confirm-row">
                    <div class="confirm-label">性別</div>
                    <div class="confirm-value">男性</div>
                    <input type="hidden" name="gender" value="男性">
                </div>
                
                <div class="confirm-row">
                    <div class="confirm-label">メールアドレス</div>
                    <div class="confirm-value">test@example.com</div>
                    <input type="hidden" name="email" value="test@example.com">
                </div>
                
                <div class="confirm-row">
                    <div class="confirm-label">電話番号</div>
                    <div class="confirm-value">08012345678</div>
                    <input type="hidden" name="tel" value="08012345678">
                </div>
                
                <div class="confirm-row">
                    <div class="confirm-label">住所</div>
                    <div class="confirm-value">東京都渋谷区千駄ヶ谷3-3-3</div>
                    <input type="hidden" name="address" value="東京都渋谷区千駄ヶ谷3-3-3">
                </div>
                
                <div class="confirm-row">
                    <div class="confirm-label">建物名</div>
                    <div class="confirm-value">千駄ヶ谷マンション901</div>
                    <input type="hidden" name="building" value="千駄ヶ谷マンション901">
                </div>
                
                <div class="confirm-row">
                    <div class="confirm-label">お問い合わせの種類</div>
                    <div class="confirm-value">商品の交換について</div>
                    <input type="hidden" name="category" value="商品の交換について">
                </div>
                
                <div class="confirm-row content-row">
                    <div class="confirm-label">お問い合わせ内容</div>
                    <div class="confirm-value">届いた商品が注文した商品ではありませんでした。商品の取り替えをお願いします。</div>
                    <input type="hidden" name="content" value="届いた商品が注文した商品ではありませんでした。商品の取り替えをお願いします。">
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="submit-button">送信</button>
                <button type="submit" formaction="/contact" formmethod="GET" class="back-button">修正</button>
            </div>
        </form>
    </div>
</body>
</html>