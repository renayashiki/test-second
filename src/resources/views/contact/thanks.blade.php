<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="stylesheet" href="{{ asset('css/contact/thanks.css') }}">
</head>
<body>
    <div class="container">
        <p class="thank-you-background">Thank you</p>
        
        <div class="message-box">
            <p class="thank-you-text">お問い合わせありがとうございました</p>
            
            <a href="{{ route('contact.index') }}" class="home-button">HOME</a>
        </div>
    </div>
</body>
</html>