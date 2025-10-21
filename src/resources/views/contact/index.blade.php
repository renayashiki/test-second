<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Contact Form</title>
    <!-- assetヘルパ関数を使用し、パスを正確にする -->
    <link rel="stylesheet" href="{{ asset('css/contact/index.css') }}">
</head>
<body>
    <header class="header">
        <h1 class="logo">FashionablyLate</h1>
        <!-- 2. 区切り線はCSSで画面幅いっぱいに広げます -->
        <hr class="header-divider">
    </header>

    <div class="container">
        <h2 class="page-title">Contact</h2>
        
        <form action="{{ route('contact.confirm') }}" method="POST" class="contact-form">
            @csrf

            <!-- お名前 (姓名分離) -->
            <div class="form-group">
                <label for="first_name" class="form-label required">お名前</label>
                <div class="form-input-group">
                    <div class="name-fields">
                        <input type="text" name="first_name" id="first_name" class="input-text name-input @error('first_name') is-invalid @enderror" placeholder="例:山田" value="{{ old('first_name') }}">
                        <input type="text" name="last_name" class="input-text name-input @error('last_name') is-invalid @enderror" placeholder="例:太郎" value="{{ old('last_name') }}">
                    </div>
                    <!-- エラーメッセージの表示 -->
                    <div class="error-messages">
                        @error('first_name')
                            <p class="error-text">姓を入力してください</p>
                        @enderror
                        @error('last_name')
                            <p class="error-text">名を入力してください</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 性別 -->
            <div class="form-group">
                <label class="form-label required">性別</label>
                <div class="form-input-group radio-group">
                    <!-- old('gender')で選択状態を保持 -->
                    <label class="radio-label">
                        <input type="radio" name="gender" value="male" {{ old('gender', 'male') == 'male' ? 'checked' : '' }}>
                        男性
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                        女性
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="other" {{ old('gender') == 'other' ? 'checked' : '' }}>
                        その他
                    </label>
                    <!-- エラーメッセージの表示 -->
                    @error('gender')
                        <p class="error-text full-width-error">性別を選択してください</p>
                    @enderror
                </div>
            </div>

            <!-- メールアドレス -->
            <div class="form-group">
                <label for="email" class="form-label required">メールアドレス</label>
                <div class="form-input-group">
                    <input type="email" name="email" id="email" class="input-text @error('email') is-invalid @enderror" placeholder="例:test@example.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="error-text">メールアドレスはメール形式で入力してください</p>
                    @enderror
                </div>
            </div>

            <!-- 電話番号 -->
            <div class="form-group">
                <label for="tel1" class="form-label required">電話番号</label>
                <div class="form-input-group tel-group">
                    <input type="tel" name="tel1" id="tel1" class="input-text tel-input @error('tel') is-invalid @enderror" value="{{ old('tel1') }}"> - 
                    <input type="tel" name="tel2" class="input-text tel-input @error('tel') is-invalid @enderror" value="{{ old('tel2') }}"> - 
                    <input type="tel" name="tel3" class="input-text tel-input @error('tel') is-invalid @enderror" value="{{ old('tel3') }}">
                    @if($errors->has('tel1') || $errors->has('tel2') || $errors->has('tel3') || $errors->has('tel'))
                         <p class="error-text full-width-error">電話番号を入力してください</p>
                    @endif
                </div>
            </div>

            <!-- 住所 -->
            <div class="form-group">
                <label for="address" class="form-label required">住所</label>
                <div class="form-input-group">
                    <input type="text" name="address" id="address" class="input-text @error('address') is-invalid @enderror" placeholder="例:東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}">
                    @error('address')
                        <p class="error-text">住所を入力してください</p>
                    @enderror
                </div>
            </div>

            <!-- 建物名 -->
            <div class="form-group">
                <label for="building" class="form-label">建物名</label>
                <div class="form-input-group">
                    <input type="text" name="building" id="building" class="input-text" placeholder="例:千駄ヶ谷マンション101" value="{{ old('building') }}">
                </div>
            </div>

            <!-- お問い合わせの種類 (セレクトボックス) -->
            <div class="form-group">
                <label for="category" class="form-label required">お問い合わせの種類</label>
                <div class="form-input-group select-wrapper">
                    <select name="category_id" id="category" class="input-select @error('category_id') is-invalid @enderror">
                        <!-- 3. プレースホルダーのように表示させるため、selectedクラスを付与 (CSSで色を制御) -->
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }} class="placeholder-option">選択してください</option>
                        <option value="1" {{ old('category_id') == 1 ? 'selected' : '' }}>商品のお届けについて</option>
                        <option value="2" {{ old('category_id') == 2 ? 'selected' : '' }}>商品の交換について</option>
                        <option value="3" {{ old('category_id') == 3 ? 'selected' : '' }}>商品トラブル</option>
                        <option value="4" {{ old('category_id') == 4 ? 'selected' : '' }}>ショップへのお問い合わせ</option>
                        <option value="5" {{ old('category_id') == 5 ? 'selected' : '' }}>その他</option>
                    </select>
                    @error('category_id')
                        <p class="error-text">お問い合わせの種類を選択してください</p>
                    @enderror
                </div>
            </div>

            <!-- お問い合わせ内容 -->
            <div class="form-group">
                <label for="content" class="form-label required">お問い合わせ内容</label>
                <div class="form-input-group">
                    <textarea name="content" id="content" class="input-textarea @error('content') is-invalid @enderror" placeholder="お問い合わせ内容をご記載ください">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="error-text">お問合せ内容は120文字以内で入力してください</p>
                    @enderror
                </div>
            </div>

            <div class="submit-button-container">
                <button type="submit" class="submit-button">確認画面</button>
            </div>
        </form>
    </div>
</body>
</html>
