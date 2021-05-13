<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Tag;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        $this->spot = $this->createSpot();

        $this->tag = Tag::factory()->create();
    }

    public function testFactoryable()
    {
        $eloquent = app(Tag::class);
        $this->assertNotEmpty($eloquent->get());
    }

    public function testTagHashtag()
    {
        $this->assertEquals('#' . $this->tag->tag_name, $this->tag->hash_tag);
        $this->assertEquals('#よく釣れる', $this->tag->hash_tag);
    }

    public function testTagBelongsToManySpots()
    {
        $this->tag->spots()->attach($this->spot);
        // refresh() で再度同じレコードを取得しなおし、リレーション先の件数が作成した件数と一致することを確認し、リレーションが問題ないことを保証
        $this->assertEquals(1, count($this->tag->refresh()->spots));
    }
}
