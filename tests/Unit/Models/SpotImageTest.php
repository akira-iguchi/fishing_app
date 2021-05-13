<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Spot;
use App\Models\SpotImage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpotImageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->spot = Spot::factory()->for($this->user)->create();
        $this->spotImage = SpotImage::factory()->for($this->spot)->create();
    }

    public function testFactoryable()
    {
        $eloquent = app(SpotImage::class);
        $this->assertNotEmpty($eloquent->get());
    }

    public function testSpotImageBelongsToSpot()
    {
        $this->assertNotEmpty($this->spotImage->spot);
    }
}
