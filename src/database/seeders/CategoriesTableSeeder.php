<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = [
            "商品のお届けについて",
            "商品の交換について",
            "商品トラブル",
            "ショップへのお問い合わせ",
            "その他"
        ];
        // ①カテゴリの名称を$contentsにまとめる

        foreach ($contents as $content) {
            DB::table('categories')->insert([
                'content' => $content,
            ]);
        // ②$contentsを一つ一つ分解して、categoriesテーブルに「'キー' => ＄変数」の形で保存！！
        }
    }
}
