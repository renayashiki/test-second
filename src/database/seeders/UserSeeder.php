<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 💡 管理者用のアカウントを一つ作成します
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com', // ログイン用メールアドレス
            'password' => Hash::make('password'), // ログイン用パスワード: 'password'
        ]);

        // テスト用アカウントも一つ作成しておくと便利です
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
