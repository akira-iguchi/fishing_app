<?php

namespace Tests\Unit\Models;

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

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->spot = Spot::factory()->for($this->user)
            ->has(SpotImage::factory(), 'spotImages')->create();

        $this->spot->spotFavorites()->attach($this->user);
    }

    public function testFactoryable()
    {
        $eloquent = app(Spot::class);
        $this->assertNotEmpty($eloquent->get());
    }

    public function testSpotBelongsToUser()
    {
        $this->assertNotEmpty($this->spot->user);
    }

    public function testSpotHasManySpotImages()
    {
        // refresh() で再度同じレコードを取得しなおし、リレーション先の件数が作成した件数と一致することを確認し、リレーションが問題ないことを保証
        $this->assertEquals(1, count($this->spot->refresh()->spotImages));
    }

    public function testSpotHasManySpotComments()
    {
        $comment = SpotComment::factory()->for($this->user)->for($this->spot)->count(5)->create();
        $this->assertEquals(5, count($this->spot->refresh()->spotComments));
    }

    public function testSpotBelongsToManyFavoriteSpots()
    {
        $this->assertEquals(1, count($this->spot->refresh()->spotFavorites));
    }

    public function testSpotIsLikedByUser()
    {
        $other_spot = Spot::factory()->for($this->user)->create();
        $this->assertEquals(true, $this->spot->refresh()->liked_by_user);
        $this->assertEquals(false, $other_spot->refresh()->liked_by_user);
    }

    public function testSpotBelongsToManyTags()
    {
        $tag = Tag::factory()->create();
        $this->spot->tags()->attach($tag);
        $this->assertEquals(1, count($this->spot->refresh()->tags));
    }

    public function testSpotBelongsToManyFishingTypes()
    {
        $fishingType = FishingType::factory()->create();
        $this->spot->fishingTypes()->attach($fishingType);
        $this->assertEquals(1, count($this->spot->refresh()->fishingTypes));
    }
}
