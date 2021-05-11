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
            'password' => bcrypt('password')
        ]);
    }

    public function testLoginView()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        // 認証されていないことを確認
        $this->assertGuest();
    }

    public function testLogin_success()
    {
        $this->assertGuest();

        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson(['user_name' => $this->user->user_name]);

        $this->assertAuthenticatedAs($this->user);
    }

    public function testLogin_validationError()
    {
        $this->assertGuest();

        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password222',
        ]);

        $response->assertStatus(422);

        $error = $response['errors']['email'][0];
        $this->assertEquals('ログイン情報が登録されていません。', $error);

        $this->assertGuest();
    }

    public function testLogout()
    {
        $this->actingAs($this->user);

        $this->assertAuthenticatedAs($this->user);
        $response = $this->json('POST', route('logout'));

        $response->assertStatus(200);

        $this->assertGuest();
    }

    public function testGuestLogin()
    {
        $this->assertGuest();

        $response = $this->json('POST', route('guestLogin'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson(['user_name' => $this->user->user_name]);

        $this->assertAuthenticatedAs($this->user);
    }
}
