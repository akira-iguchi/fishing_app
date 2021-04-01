<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;
use App\Models\Spot;
use App\Models\SpotImage;
use App\Models\SpotComment;
use App\Models\FishingType;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpotTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function testFactoryable()
    {
        $eloquent = app(Spot::class);
        $this->assertEmpty($eloquent->get());
        $user = User::factory()->create();
        $user = Spot::factory()->for($user)->create();
        $this->assertNotEmpty($eloquent->get());
    }

    public function testSpotBelongsToUser()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $this->assertNotEmpty($spot->user);
    }

    public function testSpotHasManySpotImages()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $spot_image = SpotImage::factory()->for($spot)->count(5)->create();
        $this->assertEquals(5, count($spot->refresh()->spotImages));
    }

    public function testSpotHasManySpotComments()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $comment = SpotComment::factory()->for($user)->for($spot)->count(5)->create();
        $this->assertEquals(5, count($spot->refresh()->spotComments));
    }

    public function testSpotBelongsToManyFavoriteSpots()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $spot->spotFavorites()->attach($user);
        $this->assertEquals(1, count($spot->refresh()->spotFavorites));
    }

    public function testSpotIsLikedBy()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $other_spot = Spot::factory()->for($user)->create();
        $spot->spotFavorites()->attach($user);
        $this->assertEquals(true, $spot->refresh()->isLikedBy($user));
        $this->assertEquals(false, $other_spot->refresh()->isLikedBy($user));
    }

    public function testSpotBelongsToManyTags()
    {
        $spot = $this->createSpot();
        $tag = Tag::factory()->create();
        $spot->tags()->attach($tag);
        $this->assertEquals(1, count($spot->refresh()->tags));
    }

    public function testSpotBelongsToManyFishingTypes()
    {
        $spot = $this->createSpot();
        $fishing_type = FishingType::factory()->create();
        $spot->fishingTypes()->attach($fishing_type);
        $this->assertEquals(1, count($spot->refresh()->fishingTypes));
    }
}
