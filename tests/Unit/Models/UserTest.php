<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Spot;
use App\Models\SpotImage;
use App\Models\Event;
use App\Models\SpotComment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'test@test.com']);
        $this->actingAs($this->user);

        $this->otherUser = User::factory()->create();

        $this->spot = Spot::factory()->for($this->user)
            ->has(SpotImage::factory(), 'spotImages')
            ->count(5)->create(['spot_name' => 'かもめ大橋']);
    }

    public function testFactoryable()
    {
        $eloquent = app(User::class);
        $this->assertNotEmpty($eloquent->get());
    }

    public function testUserHasManySpots()
    {
        // refresh() で再度同じレコードを取得しなおし、リレーション先の件数が作成した件数と一致することを確認し、リレーションが問題ないことを保証
        $this->assertEquals(5, count($this->user->refresh()->spots));
    }

    public function testUserHasManyEvents()
    {
        $event = Event::factory()->for($this->user)->count(5)->create();
        $this->assertEquals(5, count($this->user->refresh()->events));
    }

    public function testUserBelongsToManyFavoriteSpots()
    {
        $this->user->favoriteSpots()->attach($this->spot);
        $this->assertEquals(5, count($this->user->refresh()->favoriteSpots));
    }

    public function testUserHasManySpotComments()
    {
        $comment = SpotComment::factory()->for($this->user)->for($this->spot)->count(5)->create();
        $this->assertEquals(5, count($this->user->refresh()->spotComments));
    }

    public function testUserBelongsToManyFollowings()
    {
        $this->user->followings()->attach($this->otherUser);
        $this->assertEquals(1, count($this->user->refresh()->followings));
    }

    public function testUserBelongsToManyFollowers()
    {
        $this->user->followers()->attach($this->otherUser);
        $this->assertEquals(1, count($this->user->refresh()->followers));
    }

    public function testUserFollowedBy()
    {
        $this->user->followings()->attach($this->otherUser);
        $this->assertEquals(true, $this->otherUser->refresh()->followed_by);
        $this->assertEquals(false, $this->user->refresh()->followed_by);
    }
}
