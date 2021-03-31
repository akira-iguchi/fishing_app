<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DatabaseMigrations;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginView()
    {
        $response = $this->get('/login');
        $response->assertStatus(Response::HTTP_OK);
        // 認証されていないことを確認
        $this->assertGuest();
    }

    public function testLogin()
    {
        $this->assertGuest();
        // ダミーログイン
        $response = $this->dummyLogin();
        $response->assertStatus(Response::HTTP_OK);

        $this->assertAuthenticated();
    }

    public function testLogout()
    {
        $response = $this->dummyLogin();

        $this->assertAuthenticated();
        $response = $this->get('/logout');

        $response->assertStatus(302)
                ->assertRedirect('/');

        $this->assertGuest();
    }

    /**
     * ダミーユーザーログイン
     */
    private function dummyLogin()
    {
        $user = User::factory()->create();
        return $this->actingAs($user)
                    ->withSession(['user_id' => $user->id])
                    ->get('/');
    }
}
