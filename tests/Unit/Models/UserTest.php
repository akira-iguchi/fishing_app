<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Spot;
use App\Models\Event;
use App\Models\SpotComment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testFactoryable()
    {
        $eloquent = app(User::class);
        $this->assertEmpty($eloquent->get());
        $user = User::factory()->create();
        $this->assertNotEmpty($eloquent->get());
    }

    public function testUserHasManySpots()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->count(5)->create();
        // refresh() で再度同じレコードを取得しなおし、リレーション先の件数が作成した件数と一致することを確認し、リレーションが問題ないことを保証
        $this->assertEquals(5, count($user->refresh()->spots));
    }

    public function testUserHasManyEvents()
    {
        $user = User::factory()->create();
        $event = Event::factory()->for($user)->count(5)->create();
        $this->assertEquals(5, count($user->refresh()->events));
    }

    public function testUserBelongsToManyFavoriteSpots()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $user->favoriteSpots()->attach($spot);
        $this->assertEquals(1, count($user->refresh()->favoriteSpots));
    }

    public function testUserHasManySpotComments()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();
        $comment = SpotComment::factory()->for($user)->for($spot)->count(5)->create();
        $this->assertEquals(5, count($user->refresh()->spotComments));
    }

    public function testUserBelongsToManyFollowings()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $user->followings()->attach($other_user);
        $this->assertEquals(1, count($user->refresh()->followings));
    }

    public function testUserBelongsToManyFollowers()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $user->followers()->attach($other_user);
        $this->assertEquals(1, count($user->refresh()->followers));
    }

    public function testUserIsFollowedBy()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $user->followers()->attach($other_user);
        $this->assertEquals(true, $user->refresh()->isFollowedBy($other_user));
        $this->assertEquals(false, $other_user->refresh()->isFollowedBy($user));
    }
}
