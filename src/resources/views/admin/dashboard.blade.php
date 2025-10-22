<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理システム</title>
    
    {{-- CSSファイルのパスを src/public/css/admin/admin.css に修正 --}}
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}?v={{ time() }}"> 
</head>
<body>
    <div class="admin-wrap">
        <header class="admin-header">
            <h1 class="header-logo-admin">FashionablyLate</h1>
            <nav class="header-nav-admin">
                {{-- 管理者としてログインしているかを確認します --}}
                @auth 
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">logout</button>
                    </form>
                @endauth
            </nav>
        </header>
        
        <main class="admin-main">
            <div class="container">
                <h2 class="page-title">Admin</h2>
                <div class="dashboard-page">
                    
                    <div class="search-form-area">
                        {{-- search-form-row クラスで全体を横並びにする --}}
                        <form action="{{ route('admin.dashboard') }}" method="GET" id="search-form" class="search-form-row">
                            
                            <div class="search-group search-name-email">
                                <input type="text" name="name_email" placeholder="お名前またはメールアドレスを入力してください" value="{{ request('name_email') }}">
                            </div>

                            <div class="search-group search-short">
                                <select name="gender" class="select-gender">
                                    <option value="" disabled @if(!request('gender') || request('gender') === '性別') selected @endif>性別</option>
                                    <option value="all" @if(request('gender') === 'all') selected @endif>全て</option>
                                    <option value="1" @if(request('gender') == 1) selected @endif>男性</option>
                                    <option value="2" @if(request('gender') == 2) selected @endif>女性</option>
                                    <option value="3" @if(request('gender') == 3) selected @endif>その他</option>
                                </select>
                            </div>

                            <div class="search-group search-medium">
                                <select name="category" class="select-category">
                                    <option value="" disabled @if(!request('category')) selected @endif>お問い合わせの種類</option>
                                    {{-- $categories は DashboardController から渡されることを想定 --}}
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}" @if(request('category') == $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="search-group search-short">
                                <input type="date" name="date" class="input-date" value="{{ request('date') }}">
                            </div>

                            <div class="search-actions">
                                <button type="submit" class="search-btn">検索</button>
                                <button type="button" id="reset-btn" class="reset-btn">リセット</button>
                            </div>
                        </form>
                    </div>

                    <div class="data-area">
                        
                        {{-- エクスポートボタン専用のラッパー --}}
                        <div class="export-area">
                            <form action="{{ route('admin.export') }}" method="GET" class="export-form">
                                @foreach (request()->query() as $key => $value)
                                    @if($key != 'page')
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                <button type="submit" class="export-btn">エクスポート</button>
                            </form>
                        </div>
                        
                        {{-- 【修正点1: ページネーションリンクを右端に配置するためのラッパー】 --}}
                        <div class="pagination-area">
                            <div class="pagination-links">
                                {{-- シンプルなページネーションビューを使用 --}}
                                {{ $contacts->links('pagination::simple-bootstrap-4') }} 
                            </div>
                        </div>

                        {{-- 【修正点2: テーブル全体を囲む線のためにcontacts-tableクラスにCSSを適用】 --}}
                        <table class="contacts-table">
                            <thead>
                                <tr>
                                    <th class="col-name">お名前</th>
                                    <th class="col-gender">性別</th>
                                    <th class="col-email">メールアドレス</th>
                                    <th class="col-category">お問い合わせの種類</th>
                                    <th class="col-detail"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td class="col-name">{{ $contact->last_name }} {{ $contact->first_name }}</td>
                                        {{-- 性別の値（1, 2, 3）を日本語に変換して表示 --}}
                                        <td class="col-gender">
                                            @if($contact->gender == 1) 男性
                                            @elseif($contact->gender == 2) 女性
                                            @else その他
                                            @endif
                                        </td>
                                        <td class="col-email">{{ $contact->email }}</td>
                                        {{-- カテゴリIDを日本語名に変換して表示 --}}
                                        <td class="col-category">{{ $categories[$contact->category_id] ?? '不明' }}</td> 
                                        <td class="col-detail">
                                            <button class="detail-btn" data-contact="{{ $contact->toJson() }}">詳細</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="contact-modal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">お問い合わせ詳細</h3>
                            <button class="modal-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-detail-row"><div class="modal-label">お名前</div><div class="modal-value" id="modal-name"></div></div>
                            <div class="modal-detail-row"><div class="modal-label">性別</div><div class="modal-value" id="modal-gender"></div></div>
                            <div class="modal-detail-row"><div class="modal-label">メールアドレス</div><div class="modal-value" id="modal-email"></div></div>
                            <div class="modal-detail-row"><div class="modal-label">電話番号</div><div class="modal-value" id="modal-tel"></div></div>
                            <div class="modal-detail-row"><div class="modal-label">住所</div><div class="modal-value" id="modal-address"></div></div>
                            <div class="modal-detail-row"><div class="modal-label">建物名</div><div class="modal-value" id="modal-building"></div></div>
                            <div class="modal-detail-row"><div class="modal-label">お問い合わせの種類</div><div class="modal-value" id="modal-category"></div></div>
                            <div class="modal-detail-row"><div class="modal-label">お問い合わせ内容</div><div class="modal-value" id="modal-detail"></div></div>
                        </div>
                        <div class="modal-actions">
                            <form id="delete-form" method="POST" action="">
                                @csrf
                                {{-- 削除ルートがDELETEメソッドを要求するため、@method('DELETE')が必要 --}}
                                @method('DELETE') 
                                <button type="submit" class="delete-btn-modal">削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // お問い合わせの種類マッピングをJavaScriptで利用可能にする
        const CATEGORIES = @json($categories);

        // --- 検索フォームのリセット機能 ---
        document.getElementById('reset-btn').addEventListener('click', function() {
            // フォームのすべての入力値をリセット
            document.getElementById('search-form').reset();
            
            // 選択ボックスの値を空文字（最初の無効なオプション）に設定
            document.querySelector('.select-gender').value = "";
            document.querySelector('.select-category').value = "";
            
            // 検索フォームを送信（クエリパラメータをクリア）
            const baseUrl = '{{ route('admin.dashboard') }}'.split('?')[0];
            window.location.href = baseUrl;
        });

        // --- モーダル機能 ---
        const modal = document.getElementById('contact-modal');
        const detailButtons = document.querySelectorAll('.detail-btn');
        const closeModal = document.querySelector('.modal-close');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const contactData = JSON.parse(this.getAttribute('data-contact'));

                // 性別を日本語に変換するヘルパー関数
                const getGenderText = (value) => {
                    if (value == 1) return '男性';
                    if (value == 2) return '女性';
                    return 'その他';
                };

                // データをモーダルに挿入
                document.getElementById('modal-name').textContent = contactData.last_name + ' ' + contactData.first_name;
                document.getElementById('modal-gender').textContent = getGenderText(contactData.gender);
                document.getElementById('modal-email').textContent = contactData.email;
                document.getElementById('modal-tel').textContent = contactData.tel;
                document.getElementById('modal-address').textContent = contactData.address;
                document.getElementById('modal-building').textContent = contactData.building_name || '-';
                
                // カテゴリIDを日本語名に変換 
                document.getElementById('modal-category').textContent = CATEGORIES[contactData.category_id] || '不明'; 
                
                document.getElementById('modal-detail').textContent = contactData.detail;
                
                // 削除フォームのアクションURLを設定 
                const deleteRoute = '{{ url('admin/contact') }}/' + contactData.id;
                document.getElementById('delete-form').setAttribute('action', deleteRoute);

                modal.style.display = 'flex';
            });
        });

        // 閉じるボタン
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // モーダル外のクリックで閉じる
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>