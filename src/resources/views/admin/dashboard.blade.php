@extends('admin.layouts.app')

@section('title', '管理システム')

@section('content')
    <h2 class="page-title">管理システム</h2>
    <div class="dashboard-page">
        
        <!-- 検索フォームエリア -->
        <div class="search-form-area">
            <form action="{{ route('admin.dashboard') }}" method="GET" id="search-form" class="search-form-single-row">
                
                <!-- 名前・メールアドレス検索 -->
                <div class="search-group name-email-group">
                    <input type="text" name="name_email" placeholder="名前またはメールアドレスを入力してください" value="{{ request('name_email') }}">
                </div>

                <!-- 性別 -->
                <div class="search-group select-group gender-group">
                    <select name="gender" class="select-gender">
                        <option value="" disabled @if(!request('gender') || request('gender') === '性別') selected @endif>性別</option>
                        <option value="all" @if(request('gender') === 'all') selected @endif>全て</option>
                        <option value="男性" @if(request('gender') === '男性') selected @endif>男性</option>
                        <option value="女性" @if(request('gender') === '女性') selected @endif>女性</option>
                        <option value="その他" @if(request('gender') === 'その他') selected @endif>その他</option>
                    </select>
                </div>

                <!-- お問い合わせ種類 -->
                <div class="search-group select-group category-group">
                    <select name="category" class="select-category">
                        <option value="" disabled @if(!request('category')) selected @endif>お問い合わせの種類</option>
                        {{-- $categories は DashboardController から渡される --}}
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}" @if(request('category') == $id) selected @endif>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 日付 -->
                <div class="search-group date-group">
                    <input type="date" name="date" class="input-date" value="{{ request('date') }}">
                </div>

                <div class="search-actions">
                    <button type="submit" class="search-btn">検索</button>
                    <button type="button" id="reset-btn" class="reset-btn">リセット</button>
                </div>
            </form>
        </div>

        <!-- データを表示するテーブルエリア -->
        <div class="data-area">
            
            <div class="table-actions-and-pagination">
                <!-- エクスポートボタンを表の横幅先頭に配置 -->
                <form action="{{ route('admin.export') }}" method="GET" class="export-form">
                    {{-- 現在の検索条件を hidden フィールドとして渡す --}}
                    @foreach (request()->query() as $key => $value)
                        @if($key != 'page')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="export-btn">エクスポート</button>
                </form>

                <div class="pagination-top">
                    <div class="pagination-links">
                        {{-- Laravel標準のページネーションリンクを表示 --}}
                        {{ $contacts->links() }} 
                    </div>
                </div>
            </div>

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
                            <td class="col-gender">{{ $contact->gender }}</td>
                            <td class="col-email">{{ $contact->email }}</td>
                            {{-- カテゴリIDを日本語名に変換して表示 (category_idを使用するように修正) --}}
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
    
    <!-- モーダルウィンドウのHTML構造 -->
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
                    @method('DELETE')
                    <button type="submit" class="delete-btn-modal">削除</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // お問い合わせの種類マッピングをJavaScriptで利用可能にする
    const CATEGORIES = @json($categories);

    // --- 検索フォームのリセット機能 ---
    document.getElementById('reset-btn').addEventListener('click', function() {
        // フォームのすべての入力値をリセット
        document.getElementById('search-form').reset();
        
        // 選択ボックスのデフォルト値をクリア (placeholderの選択に戻る)
        document.querySelector('.select-gender').value = "";
        document.querySelector('.select-category').value = "";
        
        // 検索フォームを送信（クエリパラメータをクリア）
        window.location.href = '{{ route('admin.dashboard') }}';
    });

    // --- モーダル機能 ---
    const modal = document.getElementById('contact-modal');
    const detailButtons = document.querySelectorAll('.detail-btn');
    const closeModal = document.querySelector('.modal-close');

    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const contactData = JSON.parse(this.getAttribute('data-contact'));

            // データをモーダルに挿入
            document.getElementById('modal-name').textContent = contactData.last_name + ' ' + contactData.first_name;
            document.getElementById('modal-gender').textContent = contactData.gender;
            document.getElementById('modal-email').textContent = contactData.email;
            // JSONデータには tel, address, building_name が含まれている前提でそのまま使用
            document.getElementById('modal-tel').textContent = contactData.tel;
            document.getElementById('modal-address').textContent = contactData.address;
            document.getElementById('modal-building').textContent = contactData.building_name || '-';
            
            // カテゴリIDを日本語名に変換 (category_idを使用するように修正)
            document.getElementById('modal-category').textContent = CATEGORIES[contactData.category_id] || '不明'; 
            
            document.getElementById('modal-detail').textContent = contactData.detail;
            
            // 削除フォームのアクションURLを設定
            const deleteRoute = '{{ route('admin.delete', ['id' => 'TEMP_ID']) }}'.replace('TEMP_ID', contactData.id);
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
@endsection
