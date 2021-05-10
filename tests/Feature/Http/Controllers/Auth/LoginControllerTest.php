<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'guest@example.com',
            'password' => 'password'
        ]);
    }

    /**
     * @test
     */
    public function should_登録済みのユーザーを認証して返却する()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        dd(User::all());

        dd($response['errors']['email']);

        $response->assertStatus(200)
            ->assertJson(['name' => $this->user->name]);

        $this->assertAuthenticatedAs($this->user);
    }

    // public function testLoginView()
    // {
    //     $response = $this->get('/login');
    //     $response->assertStatus(200);
    //     // 認証されていないことを確認
    //     $this->assertGuest();
    // }

    // public function testLogin()
    // {
    //     $this->assertGuest();
    //     // ダミーログイン
    //     $response = $this->dummyLogin();
    //     $response->assertStatus(200);

    //     $this->assertAuthenticated();
    // }

    // public function testLogout()
    // {
    //     $response = $this->dummyLogin();

    //     $this->assertAuthenticated();
    //     $response = $this->json('POST', route('logout'));

    //     $response->assertStatus(200);

    //     $this->assertGuest();
    // }

    // /**
    //  * ゲストログイン
    //  *
    //  * @dataProvider guestLoginData
    //  * @return void
    //  */
    // public function testGuestLogin($params)
    // {
    //     $this->assertGuest();
    //     $user = User::factory()->create([
    //         'email' => 'guest@example.com',
    //         'password' => 'password'
    //     ]);

    //     $response = $this->json('POST', route('guestLogin'), $params['requestData']);

    //     $response->assertStatus(200);

    //     $this->assertAuthenticatedAs($user);
    // }

    // /**
    //  * ダミーユーザーログイン
    //  */
    // private function dummyLogin()
    // {
    //     $user = User::factory()->create();
    //     return $this->actingAs($user)
    //                 ->withSession(['user_id' => $user->id])
    //                 ->get('/');
    // }

    // public function guestLoginData()
    // {
    //     return [
    //         'valid data' => [
    //             [
    //                 'requestData' => [
    //                     'email'      => 'guest@example.com',
    //                     'password'   => 'password'
    //                 ],
    //             ]
    //         ]
    //     ];
    // }
}
