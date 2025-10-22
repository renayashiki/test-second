<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>FashionablyLate - @yield('title', '管理画面')</title>
    <!-- Interフォントを使用し、CSSを読み込む -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- 🚨 管理画面用スタイルを読み込みます -->
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <style>
        /* モーダル表示のためのCSSをここに記述（必要に応じて） */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none; 
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 20px;
            font-weight: 500;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }
        .modal-detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .modal-label {
            width: 120px;
            font-weight: 500;
            color: #555;
            flex-shrink: 0;
        }
        .modal-value {
            word-break: break-word;
            white-space: pre-wrap;
            flex-grow: 1;
        }
        .modal-actions {
            margin-top: 20px;
            text-align: right;
        }
        .delete-btn-modal {
            background-color: #E53E3E;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- ヘッダー (ログイン後の共通ヘッダー) -->
    <header class="admin-header">
        <h1 class="admin-header-logo">FashionablyLate</h1>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">ログアウト</button>
        </form>
    </header>

    <main class="admin-main">
        @yield('content')
    </main>

    <!-- モーダルウィンドウのHTML構造（コンテンツ側で定義されるモーダルはここには含めない） -->

    @yield('scripts')
</body>
</html>
