<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\FishingType;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FishingTypeTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function testFactoryable()
    {
        $eloquent = app(FishingType::class);
        $this->assertEmpty($eloquent->get());
        $user = FishingType::factory()->create();
        $this->assertNotEmpty($eloquent->get());
    }

    public function testFishingTypeBelongsToManySpots()
    {
        $spot = $this->createSpot();
        $fishing_type = FishingType::factory()->create();
        $fishing_type->spots()->attach($spot);
        $this->assertEquals(1, count($fishing_type->refresh()->spots));
    }
}
