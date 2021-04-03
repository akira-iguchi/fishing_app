<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Tag;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function testFactoryable()
    {
        $eloquent = app(Tag::class);
        $this->assertEmpty($eloquent->get());
        $tag = Tag::factory()->create();
        $this->assertNotEmpty($eloquent->get());
    }

    public function testTagBelongsToManySpots()
    {
        $spot = $this->createSpot();
        $tag = Tag::factory()->create();
        $tag->spots()->attach($spot);
        $this->assertEquals(1, count($tag->refresh()->spots));
    }
}
