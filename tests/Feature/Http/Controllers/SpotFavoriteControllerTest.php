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

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->spot = Spot::factory()->for($this->user)->create();
    }

    public function testFavorite()
    {
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $response = $this
            ->json('PUT', route('spots.favorite', [$this->spot->id, $this->user->id]));

        $response->assertStatus(200);

        $this->assertDatabaseHas('spot_favorite', [
            'spot_id' => $this->spot->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function testUnFavorite()
    {
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // 事前にリレーション
        $this->spot->spotFavorites()->attach($this->user);

        $response = $this
            ->json('DELETE', route('spots.unfavorite', [$this->spot->id, $this->user->id]));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('spot_favorite', [
            'spot_id' => $this->spot->id,
            'user_id' => $this->user->id,
        ]);
    }
}
