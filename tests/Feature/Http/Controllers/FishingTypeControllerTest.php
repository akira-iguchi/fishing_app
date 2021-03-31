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
    }

    public function testIndex()
    {
        $spot = $this->createSpot();
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);
        $spot->fishing_types()->attach($fishing_type);

        $response = $this->get('/fishing_types');

        $response->assertStatus(Response::HTTP_OK)
                    ->assertSee($fishing_type->fishing_type_name)
                    ->assertSee('サビキ釣り')
                    ->assertSee($spot->spot_name)
                    ->assertSee('かもめ大橋');
    }
}
