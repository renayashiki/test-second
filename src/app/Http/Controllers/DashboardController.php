<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    // お問い合わせの種類のマッピングを定義 (ID => 日本語名)
    private $categoryMapping = [
        1 => '商品の交換について',
        2 => '商品の返品について',
        3 => 'その他',
    ];

    /**
     * お問い合わせ一覧の表示と検索処理
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // 検索クエリの初期化
        $query = Contact::query();

        // --- 1. 名前・メールアドレス検索 (部分一致) ---
        if ($nameEmail = $request->input('name_email')) {
            $query->where(function ($q) use ($nameEmail) {
                $cleanNameEmail = trim($nameEmail);
                $nameParts = explode(' ', $cleanNameEmail);
                if (count($nameParts) > 1) {
                    $q->orWhere(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $cleanNameEmail . '%');
                }
                $q->orWhere('last_name', 'like', '%' . $cleanNameEmail . '%')
                    ->orWhere('first_name', 'like', '%' . $cleanNameEmail . '%')
                    ->orWhere('email', 'like', '%' . $cleanNameEmail . '%');
            });
        }

        // --- 2. 性別検索 ---
        if ($gender = $request->input('gender')) {
            if ($gender !== 'all' && $gender !== '性別') {
                $query->where('gender', $gender);
            }
        }

        // --- 3. お問い合わせ種類検索 ---
        // $this->categoryMapping のキーであるIDで検索
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // --- 4. 日付での検索 ---
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // ページネーション (7件ごと)
        $contacts = $query->paginate(7)->appends($request->except('page'));

        // 🚨 修正箇所: $categories をビューに渡す
        $categories = $this->categoryMapping;

        // $contacts と $categories をビューに渡す
        return view('admin.dashboard', compact('contacts', 'categories'));
    }

    /**
     * CSV形式でデータをエクスポート (応用機能)
     * @param Request $request
     * @return StreamedResponse
     */
    public function export(Request $request)
    {
        $query = Contact::query();

        // --- 検索ロジック (indexメソッドから移植) ---
        if ($nameEmail = $request->input('name_email')) {
            $query->where(function ($q) use ($nameEmail) {
                $cleanNameEmail = trim($nameEmail);
                $nameParts = explode(' ', $cleanNameEmail);
                if (count($nameParts) > 1) {
                    $q->orWhere(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $cleanNameEmail . '%');
                }
                $q->orWhere('last_name', 'like', '%' . $cleanNameEmail . '%')
                    ->orWhere('first_name', 'like', '%' . $cleanNameEmail . '%')
                    ->orWhere('email', 'like', '%' . $cleanNameEmail . '%');
            });
        }

        if ($gender = $request->input('gender')) {
            if ($gender !== 'all' && $gender !== '性別') {
                $query->where('gender', $gender);
            }
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }
        // --- 検索ロジック 終了 ---

        $categoryMapping = $this->categoryMapping; // カテゴリマッピングを取得

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contacts_export_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($query, $categoryMapping) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM を追加して Excel での文字化けを防ぐ
            fwrite($file, "\xEF\xBB\xBF");

            // CSVヘッダー行
            fputcsv($file, ['ID', '氏名', '性別', 'メールアドレス', '電話番号', '住所', '建物名', 'お問い合わせの種類', '詳細', '登録日時']);

            // データの取得と書き込み
            $query->chunk(1000, function ($contacts) use ($file, $categoryMapping) {
                foreach ($contacts as $contact) {
                    // 🚨 修正箇所: category ID を日本語名に変換して出力
                    $categoryName = $categoryMapping[$contact->category] ?? '不明';

                    fputcsv($file, [
                        $contact->id,
                        $contact->last_name . ' ' . $contact->first_name, // フルネーム
                        $contact->gender,
                        $contact->email,
                        $contact->tel,
                        $contact->address,
                        $contact->building_name,
                        $categoryName, // 日本語名
                        $contact->detail,
                        $contact->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * データの削除 (モーダル内の「削除」ボタン用)
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $contact = Contact::find($id);

        if ($contact) {
            $contact->delete();
            return redirect()->route('admin.dashboard')->with('status', 'データを削除しました。');
        }

        return redirect()->route('admin.dashboard')->with('error', '指定されたデータが見つかりませんでした。');
    }
}
