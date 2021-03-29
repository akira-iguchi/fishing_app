<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
use App\Models\FishingType;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;


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

    public function testSearch()
    {
        $spot = $this->createSpot();

        $response = $this->get('spots/search');

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee($spot->spot_name)
                ->assertSee('かもめ大橋');
    }

    // public function testShow()
    // {
    //     $spot = $this->createSpot();

    //     $response = $this->get("/spots/{$spot->id}");

    //     $response->assertStatus(Response::HTTP_OK)
                // ->assertSee($spot->spot_name)
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

    // /**
    //  * 正常系
    //  *
    //  * @dataProvider SpotData
    //  * @return void
    //  */
    // public function testStore_success($params)
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $tag = Tag::factory()->create(['name' => 'よく釣れる']);

    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

    //     $response = $this->post(route('spots.store'), $params['requestData']);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertRedirect('/');

    //     $this->assertCount(1, Spot::all());

    //     $this->assertDatabaseHas('spots', [
    //         'id'             => 1,
    //         'spot_name'      => $params['requestData']['spot_name'],
    //         'user_id'        => $user->id,
    //     ]);

    //     // 画像（子テーブル保存）
    //     $this->assertCount(1, SpotImage::all());
    //     $this->assertDatabaseHas('spot_images', [
    //         'spot_image'     => $params['requestData']['spot_image'],
    //         'spot_id'        => 1, // spotのidと同じ
    //     ]);

    //     // tagとリレーション（Dataの入れ方がわからず断念）
    //     // $this->assertDatabaseHas('spot_tag', [
    //     //     'tag_id'      => 1,
    //     //     'spot_id'     => 1, // spotのidと同じ
    //     // ]);

    //     // fishing_typeとリレーション
    //     $this->assertDatabaseHas('spot_fishing_type', [
    //         'fishing_type_id'      => $params['requestData']['fishing_types'],  // $fishing_typeのidと同じ(=1)
    //         'spot_id'              => 1, // spotのidと同じ
    //     ]);

    //     // S3に画像を保存(fake使用)
    //     Storage::fake('s3');
    //     $uploadedFile = UploadedFile::fake()->image($params['requestData']['spot_image']);
    //     $uploadedFile->storeAs('', $params['requestData']['spot_image'], ['disk' => 's3']);
    //     Storage::disk('s3')->assertExists('defaultSpot.jpg');
    // }

    // /**
    //  * 異常系: バリデーションに引っかかる
    //  *
    //  * @dataProvider validationSpotErrorData
    //  * @return void
    //  */
    // public function testStore_validationError($params)
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $response = $this->post(route('spots.store'), $params['requestData']);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertSessionHasErrors();

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

    /**
     * 正常系
     *
     * @dataProvider SpotData
     * @return void
     */
    // function testUpdate($params)
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

    //     $spot = Spot::factory()->for($user)->create(['spot_name' => '貝塚人工島']);
    //     $spot_image = SpotImage::factory()->for($spot)->create(['spot_image' => 'fishing.jpg']);

    //     $response = $this->put(route('spots.update', $spot->id), $params['requestData']);

    //     $this->assertDatabaseHas('spots', [
    //         'id'             => 1,
    //         'spot_name'      => 'かもめ大橋',  // 「貝塚人工島」が「かもめ大橋」に変更（$spot（変数）に変更はない）
    //         'user_id'        => $user->id,
    //     ]);

    //     // 画像（子テーブル変更）
    //     $this->assertDatabaseHas('spot_images', [
    //         'spot_image'     => 'defaultSpot.jpg',
    //         'spot_id'        => 1, // spotのidと同じ
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertRedirect("/spots/1")
    //             ->assertSee($params['requestData']['spot_name'])
    //             ->assertSee('かもめ大橋')
    //             ->assertSee($fishing_type->fishing_type_name)
    //             ->assertSee('サビキ釣り');
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

    // public function testFavorite()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $spot = Spot::factory()->for($user)->create();

    //     $response = $this->put(route('spots.favorite', [$spot->id, $user->id]));

    //     $response->assertStatus(Response::HTTP_OK);

    //     $this->assertDatabaseHas('spot_favorite', [
    //         'spot_id' => $spot->id,
    //         'user_id' => $user->id,
    //     ]);
    // }

    // public function testUnFavorite()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $spot = Spot::factory()->for($user)->create();

    //     // 事前にリレーション
    //     $spot->spot_favorites()->attach($user);

    //     $response = $this->delete(route('spots.unfavorite', [$spot->id, $user->id]));

    //     $response->assertStatus(Response::HTTP_OK);

    //     $this->assertDatabaseMissing('spot_favorite', [
    //         'spot_id' => $spot->id,
    //         'user_id' => $user->id,
    //     ]);
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
                        'spot_image1' => 'defaultSpot.jpg',
                        'fishing_types' => 1,
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
