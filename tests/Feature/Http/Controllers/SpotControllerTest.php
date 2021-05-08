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
use Illuminate\Support\Facades\DB;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;


class SpotControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->user2 = User::factory()->create();
    }

    // public function testIndex_logged_in()
    // {
    //     $spot = Spot::factory()->for($this->user2)
    //         ->has(SpotImage::factory(), 'spotImages')->create(['spot_name' => '貝塚人工島']);
    //     $spot->spotFavorites()->attach($this->user);
    //     $otherSpot = $this->createSpot();

    //     $tag = Tag::factory()->create();
    //     $fishing_type = FishingType::factory()->create();

    //     $this->user->followings()->attach($this->user2);
    //     $this->actingAs($this->user);

    //     $response = $this->json('GET', route('spots.index'));

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             // 検索フォーム
    //             [
    //                 [['fishing_type_name' => $fishing_type->fishing_type_name]],
    //                 [['tag_name' => $tag->tag_name]],
    //             ],
    //             // 最近の投稿
    //             [
    //                 ['spot_name' => $spot->spot_name]
    //             ],
    //             // フォローしたユーザーの投稿
    //             [
    //                 ['spot_name' => $spot->spot_name]
    //             ],
    //             // いいねランキング
    //             [
    //                 ['spot_name' => $spot->spot_name], // １位
    //                 ['spot_name' => $otherSpot->spot_name]
    //             ]
    //         ]);
    // }

    // public function testIndex_guest()
    // {
    //     $response = $this->json('GET', route('spots.index'));

    //     $response->assertStatus(200)
    //         ->assertJsonMissingExact([
    //             [['spot_name' => 'かもめ大橋']],
    //         ]);
    // }

    // /**
    //  * 検索結果（該当あり）
    //  *
    //  * @return void
    //  */
    // public function testSearch_available()
    // {
    //     $this->actingAs($this->user);
    //     $spot = $this->createSpot();
    //     $otherSpot = Spot::factory()->for($this->user)->has(SpotImage::factory(), 'spotImages')
    //             ->create(['spot_name' => '貝塚人工島']);
    //     $fishing_type = FishingType::factory()->create();

    //     $spot->fishingTypes()->attach($fishing_type);

    //     $response = $this->from(route('spots.index'))->json('GET', route('spots.search', [
    //             'searchWord' => $spot->spot_name,
    //             'fishingTypes' => [$fishing_type->id],
    //     ]));

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             // 検索フォーム
    //             [
    //                 [['fishing_type_name' => $fishing_type->fishing_type_name]],
    //             ],
    //             // 検索結果
    //             [
    //                 'current_page' => 1,
    //                 'data' =>[['spot_name' => $spot->spot_name]]
    //             ],
    //         ]);
    // }

    // /**
    //  * 検索結果（該当なし）
    //  *
    //  * @return void
    //  */
    // public function testSearch_notAvailable()
    // {
    //     $this->actingAs($this->user);
    //     $spot = $this->createSpot();
    //     $otherSpot = Spot::factory()->for($this->user)->has(SpotImage::factory(), 'spotImages')
    //             ->create(['spot_name' => '貝塚人工島']);
    //     $fishing_type = FishingType::factory()->create();

    //     $response = $this->from(route('spots.index'))->json('GET', route('spots.search', [
    //         'searchWord' => 'とっとパーク小島',
    //         'fishingTypes' => [2],
    //     ]));

    //     $response->assertStatus(200)
    //         ->assertJsonMissingExact([
    //             // 検索結果
    //             [
    //                 'current_page' => 1,
    //                 'data' =>[['spot_name' => $spot->spot_name]]
    //             ],
    //         ]);
    // }

    // /**
    //  * すべての投稿（検索フォームに記入なし）
    //  *
    //  * @return void
    //  */
    // public function testSearchAll()
    // {
    //     $this->actingAs($this->user);
    //     $spot = $this->createSpot();
    //     $otherSpot = Spot::factory()->for($this->user)->has(SpotImage::factory(), 'spotImages')
    //             ->create(['spot_name' => '貝塚人工島']);
    //     $fishing_type = FishingType::factory()->create();

    //     $response = $this->from(route('spots.index'))->json('GET', route('spots.search', [
    //         'searchWord' => '',
    //         'fishingTypes' => [],
    //     ]));

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             // 検索フォーム
    //             [
    //                 [['fishing_type_name' => $fishing_type->fishing_type_name]],
    //             ],
    //             // 検索結果
    //             [
    //                 'current_page' => 1,
    //                 'data' =>[
    //                     ['spot_name' => $spot->spot_name],
    //                     ['spot_name' => $otherSpot->spot_name]
    //                 ]
    //             ],
    //         ]);
    // }

    // public function testShow()
    // {
    //     $this->actingAs($this->user);
    //     $spot = $this->createSpot();
    //     $otherSpot = Spot::factory()->for($this->user)->has(SpotImage::factory(), 'spotImages')
    //         ->create(['spot_name' => '貝塚人工島']);

    //     $response = $this->json('GET', route('spots.show', $spot));

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             ['spot_name' => $spot->spot_name],
    //             [['spot_name' => $otherSpot->spot_name]],
    //         ]);
    // }

    // public function testCreate()
    // {
    //     $this->actingAs($this->user);
    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

    //     $response = $this->json('GET', route('spots.create'));

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             [],
    //             [
    //                 ['fishing_type_name' => $fishing_type->fishing_type_name]
    //             ],
    //         ]);
    // }

    // /**
    //  * 正常系
    //  *
    //  * @dataProvider SpotData
    //  * @return void
    //  */
    // public function testStore_success($params)
    // {
    //     $this->actingAs($this->user);
    //     $fishing_type = FishingType::factory()->create(['id' => 1]);
    //     Storage::fake('s3');

    //     $response = $this->from(route('spots.create', $this->user))
    //         ->json('POST', route('spots.store'), $params['requestData']);

    //     $response->assertStatus(201);

    //     $this->assertCount(1, Spot::all());

    //     $this->assertDatabaseHas('spots', [
    //         'spot_name'      => $params['requestData']['spot_name'],
    //         'user_id'        => $this->user->id,
    //     ]);

    //     // 画像（子テーブル保存）
    //     $this->assertCount(1, SpotImage::all());
    //     $this->assertDatabaseHas('spot_images', [
    //         'spot_id'        => Spot::where('spot_name', $params['requestData']['spot_name'])
    //                             ->pluck('id')
    //     ]);

    //     // tagとリレーション（passedValidationが通らないので保留）
    //     $this->assertDatabaseHas('spot_tag', [
    //         'tag_id'      => 1,
    //         'spot_id'     => Spot::where('spot_name', $params['requestData']['spot_name'])
    //                          ->pluck('id'), // spotのidと同じ
    //     ]);

    //     // fishing_typeとリレーション
    //     $this->assertDatabaseHas('spot_fishing_type', [
    //         'fishing_type_id'      => $params['requestData']['fishing_types'],  // $fishing_typeのidと同じ(=1)
    //         'spot_id'              => Spot::where('spot_name', $params['requestData']['spot_name'])
    //                                 ->pluck('id'), // spotのidと同じ
    //     ]);

    //     // S3に画像を保存(fake使用)
    //     $uploadedFile = $params['requestData']['spot_image1'];
    //     $uploadedFile->storeAs('', $uploadedFile, ['disk' => 's3']);
    //     Storage::disk('s3')->assertExists($uploadedFile);
    // }

    // /**
    //  * 異常系: バリデーションに引っかかる
    //  *
    //  * @dataProvider validationSpotErrorData
    //  * @return void
    //  */
    // public function testStore_validationError($params)
    // {
    //     $this->actingAs($this->user);
    //     $response = $this->from(route('spots.create', $this->user))
    //         ->json('POST', route('spots.store'), $params['requestData']);

    //     $response->assertStatus(422);

    //     $error = $response['errors']['spot_name'][0];
    //     $this->assertEquals('釣りスポット名を入力してください', $error);

    //     $this->assertCount(0, Spot::all());

    //     $this->assertDatabaseMissing('spots', [
    //         'spot_name'      => $params['requestData']['spot_name'],
    //         'user_id'        => $this->user->id,
    //     ]);
    // }

    // function testEdit()
    // {
    //     $this->actingAs($this->user);
    //     $spot = $this->createSpot();
    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);
    //     $tag = Tag::factory()->create(['tag_name' => 'よく釣れる']);

    //     $spot->tags()->attach($tag);

    //     $response = $this->get("/spots/{$spot->id}/edit");

    //     $tagName = json_encode($tag->tag_name);

    //     $response->assertStatus(Response::HTTP_OK)
    //             ->assertSee($spot->spot_name)
    //             ->assertSee('かもめ大橋')
    //             ->assertSee($fishing_type->fishing_type_name)
    //             ->assertSee('サビキ釣り')
    //             ->assertSee(trim(json_encode($tag->tag_name), '"'))
    //             ->assertSee(trim(json_encode('よく釣れる'), '"'));
    // }

    // /**
    //  * 正常系
    //  *
    //  * @dataProvider SpotData
    //  * @return void
    //  */
    // function testUpdate_success($params)
    // {
    //     $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り', 'id' => 2]);
    //     $other_fishing_type = FishingType::factory()->create(['fishing_type_name' => 'ルアー釣り', 'id' => 1]);
    //     $spot = Spot::factory()->for($this->user)->create(['spot_name' => '貝塚人工島']);
    //     $spot_image = SpotImage::factory()->for($spot)->create(['spot_image' => 'fishing.jpg']);
    //     Storage::fake('s3');


    //     // 事前にリレーション
    //     $spot->fishingTypes()->attach($fishing_type);

    //     $response = $this->from("/spots/{$spot->id}/edit")->put(route('spots.update', $spot->id), $params['requestData']);

    //     $this->assertDatabaseHas('spots', [
    //         'spot_name'      => 'かもめ大橋',  // 「貝塚人工島」が「かもめ大橋」に変更（$spot（変数）に変更はない）
    //         'user_id'        => $this->user->id,
    //     ]);

    //     // fishing_typeとリレーション
    //     $this->assertDatabaseHas('spot_fishing_type', [
    //         'fishing_type_id'      => $other_fishing_type->id,  // id = 1
    //         'spot_id'              => $spot->id
    //     ]);

    //     // 画像（子テーブル変更）
    //     $this->assertDatabaseHas('spot_images', [
    //         'spot_id'        => $spot->id
    //     ]);

    //     // S3に画像を保存(fake使用)
    //     $uploadedFile = $params['requestData']['spot_image1'];
    //     $uploadedFile->storeAs('', $uploadedFile, ['disk' => 's3']);
    //     Storage::disk('s3')->assertExists($uploadedFile);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertRedirect(route('spots.show', $spot->id));
    // }

    // /**
    //  * 異常系: バリデーションに引っかかる
    //  *
    //  * @dataProvider validationSpotErrorData
    //  * @return void
    //  */
    // function testUpdate_validationError($params)
    // {
    //     $spot = Spot::factory()->for($this->user)->create(['spot_name' => '貝塚人工島']);

    //     $response = $this->from("/spots/{$spot->id}/edit")->put(route('spots.update', $spot->id), $params['requestData']);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertSessionHasErrors();

    //     $error = session('errors')->first();
    //     $this->assertStringContainsString('釣りスポット名を入力してください', $error);

    //     $this->assertDatabaseMissing('spots', [
    //         'spot_name'      => $params['requestData']['spot_name'], // !=「サビキ釣り」
    //         'user_id'        => $this->user->id,
    //     ]);
    // }

    // public function testDestroy()
    // {
    //     $this->actingAs($this->user);
    //     $spot = $this->createSpot();

    //     // DELETE リクエスト
    //     $response = $this->delete(route('spots.destroy', $spot->id));

    //     $response->assertStatus(Response::HTTP_FOUND);

    //     $response->assertRedirect('/');

    //     $this->assertCount(0, Spot::all());
    // }

    public function SpotData()
    {
        $tagData = [
            ['text' => 'よく釣れる'],
        ];

        return [
            'valid data' => [
                [
                    'requestData' => [
                        'spot_name'     => 'かもめ大橋',
                        'explanation'   => 'テスト',
                        'address'       => '住之江区',
                        'latitude'      => 34.23,
                        'longitude'     => 135.63,
                        'spot_image1'   => UploadedFile::fake()->image('defaultSpot.jpg'),
                        'fishing_types' => 1,
                        'tags'          => json_encode($tagData),
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
                    'requestData'      => [
                        'spot_name'    => null,
                        'explanation'  => 'テスト',
                        'address'      => '住之江区',
                        'latitude'     => 34.23,
                        'longitude'    => 135.63,
                    ],
                ]
            ],
        ];
    }
}
