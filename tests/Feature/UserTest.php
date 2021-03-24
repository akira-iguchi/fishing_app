<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function createUser()
    {
        $user = new \App\Models\User;
        $user->user_name = "test";
        $user->email = "test@example.com";
        $user->password = \Hash::make('password');
        $user->save();

        $readUser = \App\Models\User::where('user_name', 'test')->first();
        $this->assertNotNull($readUser);            // データが取得できたかテスト
        $this->assertTrue(\Hash::check('password', $readUser->password)); // パスワードが一致しているかテスト
    }
}
