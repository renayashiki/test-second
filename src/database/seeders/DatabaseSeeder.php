<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Contact;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. categoriesテーブルにダミーデータを5件作成
        $this->call(CategoriesTableSeeder::class);

        // 2. contactsテーブルにダミーデータを35件作成
        Contact::factory()->count(35)->create();

        $this->call([
            // 💡 追記: 管理者用ダミーデータを作成するUserSeederを呼び出します
            UserSeeder::class,
        ]);
    }
}
