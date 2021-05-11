<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
use Illuminate\Http\Response;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTabControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->otherUser = User::factory()->create(['email' => 'test@test.com']);

        $this->spot = Spot::factory()->for($this->user)
                ->has(SpotImage::factory(), 'spotImages')->create();
    }

    public function testSpots()
    {
        $response = $this->json('GET', route('users.spots', $this->user));

        $response->assertStatus(200)
            ->assertJson([
                ['spot_name' => $this->spot->spot_name],
            ]);
    }

    public function testFavoriteSpots()
    {
        $this->user->favoriteSpots()->attach($this->spot);

        $response = $this->json('GET', route('users.favoriteSpots', $this->user));

        $response->assertStatus(200)
            ->assertJson([
                ['spot_name' => $this->spot->spot_name],
            ]);
    }

    public function testFollowings()
    {
        $this->user->followings()->attach($this->otherUser);

        $response = $this->json('GET', route('users.followings', $this->user));

        $response->assertStatus(200)
            ->assertJson([
                ['user_name' => $this->otherUser->user_name],
            ]);
    }

    public function testFollowers()
    {
        $this->user->followers()->attach($this->otherUser);

        $response = $this->json('GET', route('users.followers', $this->user));

        $response->assertStatus(200)
            ->assertJson([
                ['user_name' => $this->otherUser->user_name],
            ]);
    }
}
