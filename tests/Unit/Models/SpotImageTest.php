<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Spot;
use App\Models\SpotImage;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpotImageTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function testFactoryable()
    {
        $eloquent = app(SpotImage::class);
        $this->assertEmpty($eloquent->get());
        $spot = $this->createSpot();
        $this->assertNotEmpty($eloquent->get());
    }

    public function testSpotImageBelongsToSpot()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $spot_image = SpotImage::factory()->for($spot)->create();
        $this->assertNotEmpty($spot_image->spot);
    }
}
