<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'user_name' => 'ゲストユーザー',
            'email' => 'guest@example.com',
            'password' => Hash::make('password'),
            'introduction' => 'ポートフォリオをご覧いただきありがとうございます！
                                是非、お楽しみください！',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
