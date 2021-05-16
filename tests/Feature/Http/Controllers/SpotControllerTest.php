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
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SpotControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        // 事前にフォロー
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create(['email' => 'testtest@test.com']);
        $this->otherUser->followings()->attach($this->user);
        $this->actingAs($this->user);

        $this->spot = Spot::factory()->for($this->user)
            ->create(['spot_name' => '貝塚人工島']);
        $this->spotImage = SpotImage::factory()->for($this->spot)
            ->create(['spot_image' => 'fishing.jpg']);
        $this->spot->spotFavorites()->attach($this->user);

        $this->otherSpot = $this->createSpot();

        $this->tag = Tag::factory()->create(['tag_name' => '人が多い']);
        $this->spot->tags()->attach($this->tag);
    }

    public function testIndex_logged_in()
    {
        // $spotのidが変動しないよう1個ずつ書く
        $fishingType = FishingType::factory()->create();

        $this->actingAs($this->otherUser);
        $response = $this->json('GET', route('spots.index'));

        $response
            ->assertStatus(200)
            ->assertJson([
                // 検索フォーム
                [
                    [['fishing_type_name' => $fishingType->fishing_type_name]],
                    [['tag_name' => $this->tag->tag_name]],
                ],
                // 最近の投稿
                [
                    ['spot_name' => $this->spot->spot_name]
                ],
                // フォローしたユーザーの投稿
                [
                    ['spot_name' => $this->spot->spot_name]
                ],
                // いいねランキング
                [
                    ['spot_name' => $this->spot->spot_name], // １位
                    ['spot_name' => $this->otherSpot->spot_name]
                ]
            ]);
    }

    public function testIndex_guest()
    {
        $response = $this->json('GET', route('spots.index'));

        $response
            ->assertStatus(200)
            ->assertJsonMissingExact([
                // 検索フォーム
                [
                    [['tag_name' => $this->tag->tag_name]],
                ],
                [['spot_name' => 'かもめ大橋']],
            ]);
    }

    /**
     * 検索結果（該当あり）
     *
     * @return void
     */
    public function testSearch_available()
    {
        $fishingType = FishingType::factory()->create();

        $this->spot->fishingTypes()->attach($fishingType);

        $response = $this->from(route('spots.index'))->json('GET', route('spots.search', [
                'searchWord' => $this->spot->spot_name,
                'fishingTypes' => [$fishingType->id],
        ]));

        $response
            ->assertStatus(200)
            ->assertJson([
                // 検索フォーム
                [
                    [['fishing_type_name' => $fishingType->fishing_type_name]],
                ],
                // 検索結果
                [
                    'current_page' => 1,
                    'data' =>[['spot_name' => $this->spot->spot_name]]
                ],
            ]);
    }

    /**
     * 検索結果（該当なし）
     *
     * @return void
     */
    public function testSearch_notAvailable()
    {
        $response = $this->from(route('spots.index'))->json('GET', route('spots.search', [
            'searchWord' => 'とっとパーク小島',
            'fishingTypes' => [2],
        ]));

        $response
            ->assertStatus(200)
            ->assertJsonMissingExact([
                // 検索フォーム
                [
                    [['tag_name' => $this->tag->tag_name]],
                ],
                // 検索結果
                [
                    'current_page' => 1,
                    'data' =>[['spot_name' => $this->spot->spot_name]]
                ],
            ]);
    }

    /**
     * すべての投稿（検索フォームに記入なし）
     *
     * @return void
     */
    public function testSearchAll()
    {
        $fishingType = FishingType::factory()->create();

        $response = $this->from(route('spots.index'))->json('GET', route('spots.search', [
            'searchWord' => '',
            'fishingTypes' => [],
        ]));

        $response
            ->assertStatus(200)
            ->assertJson([
                // 検索フォーム
                [
                    [['fishing_type_name' => $fishingType->fishing_type_name]],
                ],
                // 検索結果
                [
                    'current_page' => 1,
                    'data' =>[
                        ['spot_name' => $this->spot->spot_name],
                        ['spot_name' => $this->otherSpot->spot_name]
                    ]
                ],
            ]);
    }

    public function testShow()
    {
        $response = $this->json('GET', route('spots.show', $this->spot));

        $response
            ->assertStatus(200)
            ->assertJson([
                ['spot_name' => $this->spot->spot_name],
                [['spot_name' => $this->otherSpot->spot_name]],
            ]);
    }

    public function testCreate()
    {
        $fishingType = FishingType::factory()->create();

        $response = $this->json('GET', route('spots.create'));

        $response
            ->assertStatus(200)
            ->assertJson([ [],
                [
                    ['fishing_type_name' => $fishingType->fishing_type_name]
                ],
            ]);
    }

    /**
     * 正常系
     *
     * @dataProvider SpotData
     * @return void
     */
    public function testStore_success($params)
    {
        // 釣りスポット数を０に
        Spot::query()->delete();

        $fishingType = FishingType::factory()->create(['id' => 1]);

        Storage::fake('s3');

        $response = $this->from(route('spots.create', $this->user))
            ->json('POST', route('spots.store'), $params['requestData']);

        $response->assertStatus(201);

        $this->assertCount(1, Spot::all());

        $this->assertDatabaseHas('spots', [
            'spot_name'      => $params['requestData']['spot_name'],
        ]);

        // 画像（子テーブル保存）
        $this->assertCount(1, SpotImage::all());

        // 画像（子テーブル変更）
        $this->assertDatabaseHas('spot_images', [
            'spot_id'        => Spot::where('spot_name', $params['requestData']['spot_name'])
                                ->pluck('id'), // spotのidと同じ
        ]);

        // tagとリレーション
        $this->assertDatabaseHas('spot_tag', [
            'spot_id'     => Spot::where('spot_name', $params['requestData']['spot_name'])
                             ->pluck('id'), // spotのidと同じ
        ]);

        // fishing_typeとリレーション
        $this->assertDatabaseHas('spot_fishing_type', [
            'fishing_type_id'      => $params['requestData']['fishing_types'],
            'spot_id'              => Spot::where('spot_name', $params['requestData']['spot_name'])
                                    ->pluck('id'), // 作成したspotのidと同じ
        ]);

        // S3に画像を保存(fake使用)
        $uploadedFile = $params['requestData']['spot_image_first'];
        $uploadedFile->storeAs('', $uploadedFile, ['disk' => 's3']);
        Storage::disk('s3')->assertExists($uploadedFile);
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationSpotErrorData
     * @return void
     */
    public function testStore_validationError($params)
    {
        // 釣りスポット数を０に
        Spot::query()->delete();

        $response = $this->from(route('spots.create', $this->user))
            ->json('POST', route('spots.store'), $params['requestData']);

        $response->assertStatus(422);

        $error = $response['errors']['spot_name'][0];
        $this->assertEquals('釣りスポット名を入力してください', $error);

        $this->assertCount(0, Spot::all());

        $this->assertDatabaseMissing('spots', [
            'spot_name'      => $params['requestData']['spot_name'],
            'user_id'        => $this->user->id,
        ]);
    }

    function testEdit()
    {
        $fishingType = FishingType::factory()->create();

        $response = $this->json('GET', route('spots.edit', $this->spot));

        $response
            ->assertStatus(200)
            ->assertJson([
                ['spot_name' => $this->spot->spot_name], [],
                [
                    // リレーションしたタグ
                    ['text' => $this->tag->tag_name],
                ], [],
                [
                    ['fishing_type_name' => $fishingType->fishing_type_name]
                ]
            ]);
    }

    /**
     * 正常系
     *
     * @dataProvider SpotData
     * @return void
     */
    function testUpdate_success($params)
    {
        $fishingType = FishingType::factory()->create(['id' => 1]);

        $other_fishing_type = FishingType::factory()
            ->create(['fishing_type_name' => 'ルアー釣り', 'id' => 2]);

        Storage::fake('s3');

        // 事前にリレーション
        $this->spot->fishingTypes()->attach($other_fishing_type);

        $this->tagData = [
            ['text' => 'よく釣れる'],
        ];

        $response = $this->from(route('spots.edit', $this->spot))
            ->json('PUT', route('spots.update', $this->spot), $params['requestData']);

        $response->assertStatus(201);

        $this->assertDatabaseHas('spots', [
            'spot_name'      => 'かもめ大橋',  // 「貝塚人工島」が「かもめ大橋」に変更（$this->spotに変更はない）
        ]);

        $this->assertDatabaseHas('tags', [
            'tag_name'      => '人が多い',
            'tag_name'      => 'よく釣れる',  // タグが追加される
        ]);

        // tagとのリレーション変更
        $this->assertDatabaseHas('spot_tag', [
            'tag_id'      => Tag::where('tag_name', 'よく釣れる')
                            ->pluck('id'),  // 新しくつくられたタグがリレーションされる
        ]);

        // fishing_typeとのリレーション変更
        $this->assertDatabaseHas('spot_fishing_type', [
            'fishing_type_id'      => $fishingType->id,  // id = 1
        ]);

        // S3に画像を保存(fake使用)
        $uploadedFile = $params['requestData']['spot_image_first'];
        $uploadedFile->storeAs('', $uploadedFile, ['disk' => 's3']);
        Storage::disk('s3')->assertExists($uploadedFile);

        // 画像（子テーブル変更）
        $this->assertDatabaseHas('spot_images', [
            'spot_id'        => $this->spot->id,
            'spot_image'        => json_encode($uploadedFile)
        ]);

        $response->assertStatus(201)
            ->assertJson(
                ['spot_name' => 'かもめ大橋'],  // 「貝塚人工島」が「かもめ大橋」に変更
            );
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationSpotErrorData
     * @return void
     */
    function testUpdate_validationError($params)
    {
        $response = $this->from(route('spots.edit', $this->spot))
            ->json('PUT', route('spots.update', $this->spot), $params['requestData']);

        $response->assertStatus(422);

        $error = $response['errors']['spot_name'][0];
        $this->assertEquals('釣りスポット名を入力してください', $error);

        $this->assertDatabaseMissing('spots', [
            'spot_name'      => $params['requestData']['spot_name'], // !=「サビキ釣り」
            'user_id'        => $this->user->id,
        ]);
    }

    public function testDestroy()
    {
        // 現在の釣りスポット数 = 3
        $response = $this->json('DELETE', route('spots.destroy', $this->spot));

        $response->assertStatus(200);

        $this->assertCount(2, Spot::all());
    }

    public function SpotData()
    {
        $this->tagData = [
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
                        'spot_image_first'   => UploadedFile::fake()->image('defaultSpot.jpg'),
                        'fishing_types' => 1,  // = $fishingType->id
                        'tags'          => json_encode($this->tagData),
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
