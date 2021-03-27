<?php

namespace Tests\Factories\Traits;

use App\Models\User;
use App\Models\Spot;
use App\Models\SpotImage;

trait CreateSpot
{
    private function createSpot(): Spot
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $spot = Spot::factory()->for($user)->create(['spot_name' => 'かもめ大橋']);
        $spot_image = SpotImage::factory()->for($spot)->create();

        return $spot;
    }
}