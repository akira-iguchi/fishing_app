<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\FishingType;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;


class FishingTypeControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->spot = $this->createSpot();

        $this->fishingType = FishingType::factory()->create();
    }

    public function testInvoke()
    {
        $this->spot->fishingTypes()->attach($this->fishingType);

        $response = $this->json('GET', route('fishing_types'));

        $response
            ->assertStatus(200)
            ->assertJson([[
                'fishing_type_name' => $this->fishingType->fishing_type_name,
                'spots' => [['spot_name' => $this->spot->spot_name]]
            ]]);
    }
}
