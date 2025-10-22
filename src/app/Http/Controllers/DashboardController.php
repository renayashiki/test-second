<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
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

                // Full name search (assuming contacts table has last_name and first_name)
                $nameParts = explode(' ', $cleanNameEmail);

                if (count($nameParts) > 1) {
                    // For performance, this complex DB::raw may need optimization/indexing
                    $q->orWhere(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $cleanNameEmail . '%');
                }

                // Partial match on last_name, first_name, or email
                $q->orWhere('last_name', 'like', '%' . $cleanNameEmail . '%')
                    ->orWhere('first_name', 'like', '%' . $cleanNameEmail . '%')
                    ->orWhere('email', 'like', '%' . $cleanNameEmail . '%');
            });
        }

        // --- 2. 性別検索 ---
        if ($gender = $request->input('gender')) {
            if ($gender !== 'all' && $gender !== '性別') { // 「性別」というプレースホルダーも無視
                $query->where('gender', $gender);
            }
        }

        // --- 3. お問い合わせ種類検索 ---
        if ($category = $request->input('category')) {
            $query->where('category', $category); // Complete match
        }

        // --- 4. 日付での検索 ---
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // ページネーション (7件ごと)
        // 🚨 修正箇所: withQueryString() の代わりに appends() を使用
        // appends() に $request->except('page') を渡すことで、現在の検索クエリを保持します。
        $contacts = $query->paginate(7)->appends($request->except('page'));

        return view('admin.dashboard', compact('contacts'));
    }

    /**
     * CSV形式でデータをエクスポート (応用機能)
     * @param Request $request
     * @return StreamedResponse
     */
    public function export(Request $request)
    {
        // 検索クエリの初期化
        $query = Contact::query();

        // 🚨 注意: indexメソッドの検索ロジックをここに移植します。
        // リクエストから検索条件を取得し、エクスポート対象を絞り込みます。

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
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // --- 4. 日付での検索 ---
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }


        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contacts_export_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM to prevent garbled characters in Excel
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($file, ['ID', '氏名', '性別', 'メールアドレス', '電話番号', '住所', '建物名', 'お問い合わせの種類', '詳細', '登録日時']);

            // Fetch and write data in chunks
            $query->chunk(1000, function ($contacts) use ($file) {
                foreach ($contacts as $contact) {
                    fputcsv($file, [
                        $contact->id,
                        $contact->last_name . ' ' . $contact->first_name,
                        $contact->gender,
                        $contact->email,
                        $contact->tel,
                        $contact->address,
                        $contact->building_name,
                        $contact->category,
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
     * データの削除
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
