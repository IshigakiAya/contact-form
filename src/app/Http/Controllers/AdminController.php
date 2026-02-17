<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Date;


class AdminController extends Controller
{
    /**
     * 管理画面トップ（一覧表示）
     */
    public function index()
    {
        $contacts = Contact::with('category')->get();
        //with()メソッドを使用して、リレーション関係にあるCategoryモデルのデータを取得//

        $categories = Category::all();

        $contacts = Contact::paginate(7);
        //ページネーションを使用して、1ページに7件のデータを表示//

        return view('admin', compact('contacts', 'categories'));
    }

    /**
     * お問い合わせ削除
     */
    public function destroy($id)
    {
        Contact::destroy($id);
        return redirect()->route('admin.index');
    }

    /**
     * 検索処理
     */
    public function search(Request $request)
    {
        if ($request->has('reset')) {
            return redirect('/admin')->withInput();}
        //リセットボタン：模範解答

        // 検索スコープをチェーンして、最後にpaginateする
        $contacts = Contact::with('category')
        ->keywordSearch($request->keyword)->genderSearch($request->gender)->categorySearch($request->category_id)
        ->dateSearch($request->created_at)
        ->paginate(7)
        ->appends($request->query());
        //appends()メソッドを付けることで、ページ移動時も検索条件が維持される//

        $categories = Category::all();

        return view('admin', compact('contacts', 'categories'));
    }

    /**
     * CSVエクスポート
     */
    public function export(Request $request)
    {
        $query = Contact::with('category')
        ->keywordSearch($request->keyword)
        ->genderSearch($request->gender)
        ->categorySearch($request->category_id)
        ->dateSearch($request->created_at);

        $csvData = $query->get()->toArray();

        $csvHeader = ['ID', 'カテゴリ', '姓', '名', '性別', 'メールアドレス', '電話番号', '住所', '建物名', 'お問い合わせ内容', '作成日時', '更新日時'];
        //Excelなどで見たときにヘッダーとなる部分（列名）

        // CSVデータの出力をStreamedResponseで作る
        //これにより大量データでもメモリを大量に使わない。
        //ブラウザに「CSVファイルを受け取ってね」と指示する。
        $response = new StreamedResponse(function() use ($csvHeader, $query) {
            $handle = fopen('php://output', 'w');
            // 出力先を「php://output」に設定（直接ブラウザに送る）

            // --- ヘッダーの変換と書き込み ---
            $sjisHeader = array_map(function($value) {
                return mb_convert_encoding($value, 'SJIS-win', 'UTF-8');
            }, $csvHeader);
            fputcsv($handle, $sjisHeader);
            // fputcsv関数を使って、1行目のヘッダーを書き込み

            // --- データの変換と書き込み ---
            // 大量データでもメモリを食わないように cursor() で1件ずつ処理
            foreach ($query->cursor() as $contact) {
                $row = [
                    $contact->id,
                    $contact->category->content ?? '', // カテゴリ名を表示
                    $contact->first_name,
                    $contact->last_name,
                    ($contact->gender == 1) ? '男性' : (($contact->gender == 2) ? '女性' : 'その他'), // 性別の数値をテキストに変換
                    $contact->email,
                    $contact->tel,
                    $contact->address,
                    $contact->building,
                    $contact->detail,
                    $contact->created_at,
                    $contact->updated_at,
                    // created_at, updated_atを日本時間に変換
                    Date::make($contact->created_at)->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s'),
                    Date::make($contact->updated_at)->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s'),
                ];

                // 1項目ずつ安全に文字コード変換をかける
                // array_map を使うことで、一気に全項目を変換
                $sjisRow = array_map(function($value) {
                    return mb_convert_encoding($value, 'SJIS-win', 'UTF-8');
                }, $row);

                // 1行ずつ書き込む
                fputcsv($handle, $sjisRow);
            }

            fclose($handle);
        }, 200, [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contacts.csv"',
            // CSVとしてダウンロードさせる
        ]);

        return $response;
        // レスポンスとして返す（ブラウザがCSVダウンロードを始める）
    }
}
