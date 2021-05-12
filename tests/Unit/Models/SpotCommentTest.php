<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Spot;
use App\Models\SpotImage;
use App\Models\SpotComment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpotCommentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->spot = Spot::factory()->for($this->user)
            ->has(SpotImage::factory(), 'spotImages')->create();

        $this->comment = SpotComment::factory()->for($this->user)->for($this->spot)->create();
    }

    public function testFactoryable()
    {
        $eloquent = app(SpotComment::class);
        $this->assertNotEmpty($eloquent->get());
    }

    public function testSpotImageBelongsToUser()
    {
        $this->assertNotEmpty($this->comment->user);
    }

    public function testSpotImageBelongsToSpot()
    {
        $this->assertNotEmpty($this->comment->spot);
    }
}
