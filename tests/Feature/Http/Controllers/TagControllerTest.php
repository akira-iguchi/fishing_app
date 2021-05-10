<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;
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

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->spot = $this->createSpot();
        $this->tag = Tag::factory()->create();
        $this->fishing_type = FishingType::factory()->create();
    }

    /**
     * タグとリレーションの釣りスポットの検索結果（該当あり）
     *
     * @return void
     */
    public function testInvoke_available()
    {
        $this->spot->tags()->attach($this->tag);

        $response = $this->from(route('spots.index'))
            ->json('GET', route('tags', ['name' => $this->tag->tag_name]));

        $response->assertStatus(200)
            ->assertJson([
                // 検索フォーム
                [
                    [['fishing_type_name' => $this->fishing_type->fishing_type_name]],
                    [['tag_name' => $this->tag->tag_name]],
                ],
                ['tag_name' => $this->tag->tag_name],
                // タグとリレーションしている釣りスポット
                [
                    ['spot_name' => $this->spot->spot_name]
                ],
            ]);
    }

    /**
     * タグとリレーションの釣りスポットの検索結果（該当なし）
     *
     * @return void
     */
    public function testInvoke_notAvailable()
    {
        $response = $this->from(route('spots.index'))
            ->json('GET', route('tags', ['name' => $this->tag->tag_name]));

        $response->assertStatus(200)
            ->assertJsonMissingExact([
                // 検索フォーム
                [
                    [['fishing_type_name' => $this->fishing_type->fishing_type_name]],
                    [['tag_name' => $this->tag->tag_name]],
                ],
                // 該当なし
                [],
            ]);
    }
}
