<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
use App\Models\FishingType;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;


class FishingTypeControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testIndex()
    {
        $spot = $this->createSpot();
        $fishing_type = FishingType::factory()->create();
        $spot->fishingTypes()->attach($fishing_type);

        $response = $this->json('GET', route('fishing_types'));

        $response->assertStatus(200)->assertJson([[
            'fishing_type_name' => $fishing_type->fishing_type_name,
            'spots' => [['spot_name' => $spot->spot_name]]
        ]]);
    }
}
