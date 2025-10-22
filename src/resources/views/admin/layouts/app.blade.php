<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | FashionablyLate</title>
    <!-- 認証画面の共通CSSを継承 -->
    <link rel="stylesheet" href="{{ asset('css/auth/common.css') }}">
    <!-- 管理画面専用CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <!-- 必要なフォントの読み込み (common.cssで定義されているTimes New Romanなどを想定) -->
</head>
<body>
    <div id="admin-container">
        <!-- 共通ヘッダー -->
        <header class="app-header">
            <div class="header-logo">FashionablyLate</div>
            <nav class="header-nav">
                <!-- ログアウトボタン -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="header-link logout-btn">logout</button>
                </form>
            </nav>
        </header>

        <!-- メインコンテンツとサイドバーのコンテナ -->
        <main class="admin-main">
            <!-- サイドバー（ここでは簡略化。見本画像にはないので、コンテンツ部分に統合します） -->
            
            <!-- コンテンツエリア -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>

    </div>

    <!-- モーダルウィンドウのHTML構造 (詳細表示用) -->
    <div id="detail-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <!-- 閉じるボタン -->
            <button class="modal-close-btn">&times;</button>
            <div id="modal-detail-body">
                <!-- 詳細データがここに動的に挿入されます -->
                <h2 class="modal-title">お問い合わせ内容 詳細</h2>
                <div class="modal-detail-grid">
                    <div class="detail-label">お名前</div>
                    <div class="detail-value" data-field="name"></div>
                    
                    <div class="detail-label">性別</div>
                    <div class="detail-value" data-field="gender"></div>
                    
                    <div class="detail-label">メールアドレス</div>
                    <div class="detail-value" data-field="email"></div>
                    
                    <div class="detail-label">電話番号</div>
                    <div class="detail-value" data-field="tel"></div>
                    
                    <div class="detail-label">住所</div>
                    <div class="detail-value" data-field="address"></div>
                    
                    <div class="detail-label">建物名</div>
                    <div class="detail-value" data-field="building"></div>
                    
                    <div class="detail-label">お問い合わせの種類</div>
                    <div class="detail-value" data-field="category"></div>
                </div>

                <div class="detail-label full-width">お問い合わせ内容</div>
                <div class="detail-value full-width text-area-like" data-field="detail"></div>
            </div>
            <button id="delete-from-modal-btn" class="delete-btn">削除</button>
        </div>
    </div>

    <script>
        // JavaScript for Modal (詳細表示/削除機能のために必要)
        const detailModal = document.getElementById('detail-modal');
        const modalCloseBtn = document.querySelector('.modal-close-btn');
        const deleteFromModalBtn = document.getElementById('delete-from-modal-btn');
        let currentContactId = null; 

        // 詳細ボタンクリック時の処理
        document.querySelectorAll('.detail-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const contactData = JSON.parse(e.target.dataset.contact);
                currentContactId = contactData.id; 

                // モーダルにデータを挿入 (実際のデータ構造に合わせてキーを調整してください)
                document.querySelector('[data-field="name"]').textContent = contactData.name || 'N/A';
                document.querySelector('[data-field="gender"]').textContent = contactData.gender || 'N/A';
                document.querySelector('[data-field="email"]').textContent = contactData.email || 'N/A';
                document.querySelector('[data-field="tel"]').textContent = contactData.tel || 'N/A';
                document.querySelector('[data-field="address"]').textContent = contactData.address || 'N/A';
                document.querySelector('[data-field="building"]').textContent = contactData.building || 'N/A';
                document.querySelector('[data-field="category"]').textContent = contactData.category || 'N/A';
                document.querySelector('[data-field="detail"]').textContent = contactData.detail || 'N/A';

                detailModal.style.display = 'flex';
            });
        });

        // 閉じるボタン or オーバーレイクリックでモーダルを閉じる
        modalCloseBtn.addEventListener('click', () => {
            detailModal.style.display = 'none';
        });

        detailModal.addEventListener('click', (e) => {
            if (e.target === detailModal) {
                detailModal.style.display = 'none';
            }
        });

        // 削除ボタンクリック時の処理 (モーダル内)
        deleteFromModalBtn.addEventListener('click', () => {
            if (currentContactId) {
                // 実際にはAJAXリクエストで削除エンドポイントを叩きます
                // 例: fetch(`/api/contact/${currentContactId}`, { method: 'DELETE' }) ...
                
                if (window.confirm('本当にこのデータを削除しますか？')) {
                    // ここに削除のためのフォーム送信やAjax処理を記述
                    console.log(`Contact ID ${currentContactId} を削除`);
                    
                    // 削除後、モーダルを閉じる
                    detailModal.style.display = 'none';
                    // ページの再読み込み、または削除された行をDOMから削除する処理
                }
            }
        });
    </script>
</body>
</html>
