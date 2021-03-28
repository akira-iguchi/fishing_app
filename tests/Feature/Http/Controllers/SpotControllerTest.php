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

    // public function testIndex()
    // {
    //     $spot = $this->createSpot();

    //     $tag = Tag::factory()->create(['name' => 'よく釣れる']);

    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

    //     $response = $this->get('/');

    //     $response->assertStatus(Response::HTTP_OK);
                    // ->assertSee($spot->spot_name);
                    // ->assertSee('かもめ大橋');
                    // ->assertSee($tag->name);
                    // ->assertSee('よく釣れる');
                    // ->assertSee($fishing_type->fishing_type_name);
                    // ->assertSee('サビキ釣り');
    // }

    // public function testShow()
    // {
    //     $spot = $this->createSpot();

    //     $response = $this->get("/spots/{$spot->id}");

    //     $response->assertStatus(Response::HTTP_OK);
                // ->assertSee($spot->spot_name);
                // ->assertSee('かもめ大橋');
    // }

    // public function testCreate()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

    //     $response = $this->get('/spots/create');

    //     $response->assertStatus(Response::HTTP_OK);
                // ->assertSee('name="spot[spot_name]"');
            //     ->assertSee($fishing_type->fishing_type_name);
            //     ->assertSee('サビキ釣り');
    // }

    /**
     * 正常系
     *
     * @dataProvider SpotData
     * @return void
     */
    public function testStore_success($params)
    {
        // $this->markTestIncomplete('ファイル入出力の部分がどうしてもテストが書けないので後日改めて時間を取って書く。')

        $user = User::factory()->create();
        $this->actingAs($user);

        // POST リクエスト
        $response = $this->post(route('spots.store'), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect('/');

        $this->assertCount(1, Spot::all());

        $this->assertDatabaseHas('spots', [
            'spot_name'      => $params['requestData']['spot_name'],
            'explanation'    => $params['requestData']['explanation'],
            'address'        => $params['requestData']['address'],
            'latitude'       => $params['requestData']['latitude'],
            'longitude'      => $params['requestData']['longitude'],
            'user_id'        => $user->id,
        ]);
    }

    // /**
    //  * 異常系: バリデーションに引っかかる
    //  *
    //  * @dataProvider validationSpotErrorData
    //  * @return void
    //  */
    // public function testStoreValidationError($params)
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $response = $this->post(route('spots.store'), $params['requestData']);

    //     $response->assertStatus(Response::HTTP_FOUND);
            //     ->assertSessionHasErrors();

    //     $error = session('errors')->first();
    //     $this->assertStringContainsString('釣りスポット名を入力してください', $error);

    //     $this->assertCount(0, Spot::all());

    //     $this->assertDatabaseMissing('spots', [
    //         'spot_name'      => $params['requestData']['spot_name'],
    //         'explanation'    => $params['requestData']['explanation'],
    //         'address'        => $params['requestData']['address'],
    //         'latitude'       => $params['requestData']['latitude'],
    //         'longitude'      => $params['requestData']['longitude'],
    //         'user_id'        => $user->id,
    //     ]);
    // }

    // function testEdit()
    // {
    //     $spot = $this->createSpot();

    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

    //     $response = $this->get("/spots/{$spot->id}/edit");

    //     $response->assertStatus(Response::HTTP_OK)
    //             ->assertSee($spot->spot_name)
    //             ->assertSee('かもめ大橋')
    //             ->assertSee($fishing_type->fishing_type_name)
    //             ->assertSee('サビキ釣り');
    // }

    // /**
    //  * 正常系
    //  *
    //  * @dataProvider SpotData
    //  * @return void
    //  */
    // function testUpdate($params)
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $spot = Spot::factory()->for($user)->create(['spot_name' => '貝塚人工島']);
    //     $spot_image = SpotImage::factory()->for($spot)->create(['spot_image' => 'fishing.jpg']);

    //     // $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

    //     $response = $this->put(route('spots.update', $spot->id), $params['requestData']);

    //     dd($spot->spot_name);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertRedirect("/spots/{$spot->id}")
    //             ->assertSee($spot->spot_name)
    //             ->assertSee('かもめ大橋');
    //             ->assertSee($fishing_type->fishing_type_name)
    //             ->assertSee('サビキ釣り');

    //             $spot = Spot::factory()->has($fishing_type)->for($user)->create(['spot_name' => 'かもめ大橋']);
    // }

    // public function testDestroy()
    // {
    //     $spot = $this->createSpot();

    //     // DELETE リクエスト
    //     $response = $this->delete(route('spots.destroy', $spot->id));

    //     $response->assertStatus(Response::HTTP_FOUND);

    //     $response->assertRedirect('/');

    //     // `spots` テーブルが1件になっている
    //     $this->assertCount(0, Spot::all());
    // }

    public function SpotData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'spot_name' => 'かもめ大橋',
                        'explanation' => 'テスト',
                        'address' => '住之江区',
                        'latitude' => 34.23,
                        'longitude' => 135.63,
                        'spot_image' => 'defaultSpot.jpg',
                    ],
                ]
            ]
        ];
    }

    public function validationSpotErrorData()
    {
        return [
            // spot_nameのバリデーションのみ
            'validation: 釣りスポット名を入力してください' => [
                [
                    'requestData' => [
                        'spot_name' => null,
                        'explanation' => 'テスト',
                        'address' => '住之江区',
                        'latitude' => 34.23,
                        'longitude' => 135.63,
                        'spot_image' => 'defaultSpot.jpg',
                    ],
                ]
            ],
        ];
    }
}
