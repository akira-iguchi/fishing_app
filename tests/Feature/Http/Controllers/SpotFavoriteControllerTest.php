<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SpotFavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFavorite()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->create();

        $response = $this->put(route('spots.favorite', [$spot->id, $user->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('spot_favorite', [
            'spot_id' => $spot->id,
            'user_id' => $user->id,
        ]);
    }

    public function testUnFavorite()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->create();

        // 事前にリレーション
        $spot->spotFavorites()->attach($user);

        $response = $this->delete(route('spots.unfavorite', [$spot->id, $user->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('spot_favorite', [
            'spot_id' => $spot->id,
            'user_id' => $user->id,
        ]);
    }
}
