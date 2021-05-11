<?php

namespace Tests\Factories\Traits;

use App\Models\User;
use App\Models\Spot;
use App\Models\SpotImage;

trait CreateSpot
{
    private function createSpot(): Spot
    {
        $user = User::factory()->create(['email' => 'test@test.com']);
        $this->actingAs($user);

        $spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => 'かもめ大橋']);

        return $spot;
    }
}