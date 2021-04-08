<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTabControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSpots()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => 'かもめ大橋']);

        $response = $this->get("/users/{$user->id}/spots");

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson([['spot_name' => $spot->spot_name]]);
    }

    public function testFavoriteSpots()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => 'かもめ大橋']);

        $user->favoriteSpots()->attach($spot);

        $response = $this->get("/users/{$user->id}/favoriteSpots");

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson([['spot_name' => $spot->spot_name]]);
    }

    public function testFollowings()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $other_user = User::factory()->create();

        $user->followings()->attach($other_user);

        $response = $this->get("/users/{$user->id}/followings");

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson([['user_name' => $other_user->user_name]]);
    }

    public function testFollowers()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $other_user = User::factory()->create();

        $user->followers()->attach($other_user);

        $response = $this->get("/users/{$user->id}/followers");

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson([['user_name' => $other_user->user_name]]);
    }
}
