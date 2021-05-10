<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->otherUser = User::factory()
            ->create(['email' => 'test@test.com']);
        $this->actingAs($this->user);
    }

    // public function testFollow()
    // {
    //     $response = $this->json(
    //         'PUT', route('users.follow', [$this->otherUser])
    //     );

    //     $response->assertStatus(200);

    //     $this->assertDatabaseHas('follows', [
    //         'follower_id' => $this->user->id,
    //         'followee_id' => $this->otherUser->id,
    //     ]);
    // }

    /**
     * 自信をフォローはできない
     *
     * @return void
     */
    public function testFollow_myself()
    {
        $response = $this->json(
            'PUT', route('users.follow', [$this->user])
        );

        $response->assertStatus(404);

        $this->assertDatabaseMissing('follows', [
            'follower_id' => $this->user->id,
            'followee_id' => $this->user->id,
        ]);
    }

    public function testUnFollow()
    {
        // 事前にフォロー
        $this->user->followings()->attach($this->otherUser);

        $response = $this->json(
            'DELETE', route('users.unfollow', [$this->otherUser])
        );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('follows', [
            'follower_id' => $this->user->id,
            'followee_id' => $this->otherUser->id,
        ]);
    }
}
