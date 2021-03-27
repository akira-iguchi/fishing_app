<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
use App\Models\FishingType;
use Illuminate\Http\Response;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SpotControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex()
    {
        $spot = $this->createSpot();

        $tag = Tag::factory()->create(['name' => 'よく釣れる']);

        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertSee($spot->spot_name);
        $response->assertSee('かもめ大橋');

        $response->assertSee($tag->name);
        $response->assertSee('よく釣れる');

        $response->assertSee($fishing_type->fishing_type_name);
        $response->assertSee('サビキ釣り');
    }

    // public function testShow()
    // {
    //     $user = User::factory()->create();
    //     $spot = Spot::factory()->for($user)->create();
    //     // GET リクエスト
    //     $response = $this->get(route('spots.show', [$spot->id]));

    //     // レスポンスの検証
    //     $response->assertOk();
    // }

    // public function testCreate()
    // {
    //     // GET リクエスト
    //     $response = $this->get(route('spots.create'));

    //     $response->assertOk();
    // }

    // public function testStore()
    // {
    //     $data = [
    //         'spots' => [
    //             'spot_name' => 'かもめ大橋',
    //             'explanation' => 'テスト',
    //             'address' => '住之江区',
    //             'latitude' => 34.23,
    //             'longitude' => 135.63,
    //             'user_id' => 1,
    //         ],
    //     ];

    //     // POST リクエスト
    //     $response = $this->post(route('spots.store'), $data);

    //     $response->assertStatus(302);

    //     $response->assertRedirect('/');

    //     $this->assertCount(0, Spot::all());
    // }

    // public function testDestroy()
    // {
    //     $user = User::factory()->create();
    //     $spot = Spot::factory()->for($user)->create();

    //     // DELETE リクエスト
    //     $response = $this->delete(route('spots.destroy', $spot->id));

    //     $response->assertStatus(302);

    //     $response->assertRedirect('/');

    //     // `spots` テーブルが1件になっている
    //     $this->assertCount(0, Spot::all());
    // }
}
