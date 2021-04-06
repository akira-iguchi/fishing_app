<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\Spot;
use App\Models\FishingType;
use Illuminate\Http\Response;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;


class TagControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * タグとリレーションの釣りスポットの検索結果（該当あり）
     *
     * @return void
     */
    public function testShow_available()
    {
        $spot = $this->createSpot();
        $tag = Tag::factory()->create(['name' => 'よく釣れる']);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $spot->tags()->attach($tag);

        $response = $this->from('/')->get(route('tags', ['name' => $tag->name]));

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee('1件')
                ->assertSee($spot->spot_name)
                ->assertSee('かもめ大橋')
                ->assertSee($fishing_type->fishing_type_name)
                ->assertSee('サビキ釣り');
    }

    /**
     * タグとリレーションの釣りスポットの検索結果（該当なし）
     *
     * @return void
     */
    public function testShow_notAvailable()
    {
        $spot = $this->createSpot();
        $tag = Tag::factory()->create(['name' => 'よく釣れる']);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $response = $this->from('/')->get(route('tags.show', ['name' => $tag->name]));

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee('0件')
                ->assertDontSee($spot->spot_name)
                ->assertDontSee('かもめ大橋')
                ->assertSee($fishing_type->fishing_type_name)
                ->assertSee('サビキ釣り');
    }
}
