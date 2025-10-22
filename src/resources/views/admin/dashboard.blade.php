@extends('admin.layouts.app')

@section('content')
    <div class="dashboard-page">
        <h1 class="page-title">確認テスト お問い合わせフォーム</h1>

        <!-- 検索フォームエリア -->
        <div class="search-form-area">
            <form action="{{ route('admin.dashboard') }}" method="GET" id="search-form">
                <div class="search-row">
                    <!-- 名前・メールアドレス検索 -->
                    <div class="search-group name-email-group">
                        <input type="text" name="name_email" placeholder="名前またはメールアドレスを入力してください" value="{{ request('name_email') }}">
                    </div>

                    <!-- 性別 -->
                    <div class="search-group select-group">
                        <select name="gender" class="select-gender">
                            <option value="" disabled selected>性別</option>
                            <option value="all" @if(request('gender') === 'all') selected @endif>全て</option>
                            <option value="男性" @if(request('gender') === '男性') selected @endif>男性</option>
                            <option value="女性" @if(request('gender') === '女性') selected @endif>女性</option>
                            <option value="その他" @if(request('gender') === 'その他') selected @endif>その他</option>
                        </select>
                    </div>

                    <!-- お問い合わせ種類 -->
                    <div class="search-group select-group">
                        <select name="category" class="select-category">
                            <option value="" disabled selected>お問い合わせの種類</option>
                            <!-- 実際にはDBから取得したカテゴリをループ -->
                            <option value="商品の交換について" @if(request('category') === '商品の交換について') selected @endif>商品の交換について</option>
                            <option value="商品の返品について" @if(request('category') === '商品の返品について') selected @endif>商品の返品について</option>
                        </select>
                    </div>

                    <!-- 日付 -->
                    <div class="search-group date-group">
                        <input type="date" name="date" class="input-date" value="{{ request('date') }}">
                    </div>
                </div>

                <div class="search-actions">
                    <button type="submit" class="search-btn">検索</button>
                    <button type="button" id="reset-btn" class="reset-btn">リセット</button>
                </div>
            </form>
        </div>

        <!-- データを表示するテーブルエリア -->
        <div class="data-area">
            <div class="table-actions">
                <button class="export-btn">エクスポート</button>
            </div>
            
            <div class="pagination-top">
                <!-- 実際には $contacts->links('pagination::admin') などを使用 -->
                <div class="pagination-info">全{{ $contacts->total() ?? 14 }}件中 {{ $contacts->firstItem() ?? 1 }}〜{{ $contacts->lastItem() ?? 7 }}件表示</div>
                <div class="pagination-links">
                    {{-- ページネーションリンクをここに表示 --}}
                    <div class="dummy-links">
                        <a href="#" class="page-link prev-link">&lt;</a>
                        <span class="page-link active">1</span>
                        <a href="#" class="page-link">2</a>
                        <a href="#" class="page-link">3</a>
                        <a href="#" class="page-link">4</a>
                        <a href="#" class="page-link">5</a>
                        <a href="#" class="page-link next-link">&gt;</a>
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
                    <!-- 実際には $contacts をループ -->
                    @foreach ($contacts as $contact)
                        <tr>
                            <td class="col-name">{{ $contact['name'] }}</td>
                            <td class="col-gender">{{ $contact['gender'] }}</td>
                            <td class="col-email">{{ $contact['email'] }}</td>
                            <td class="col-category">{{ $contact['category'] }}</td>
                            <td class="col-detail">
                                <!-- data-contactにJSON形式でデータを埋め込み、JSで利用 -->
                                <button class="detail-btn" data-contact="{{ json_encode($contact) }}">詳細</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // リセットボタン機能
        document.getElementById('reset-btn').addEventListener('click', function() {
            // フォームのすべての入力値をリセット
            document.getElementById('search-form').reset();
            
            // 選択ボックスのデフォルトを「性別」に戻す
            document.querySelector('.select-gender').value = "";
            document.querySelector('.select-category').value = "";
            
            // 検索フォームを送信（リセット後の結果を表示するため）
            document.getElementById('search-form').submit();
        });
    </script>
@endsection

