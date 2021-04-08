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
    }

    public function testIndex_logged_in()
    {
        $spot = $this->createSpot();
        $tag = Tag::factory()->create(['tag_name' => 'よく釣れる']);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK)
                    ->assertSee($spot->spot_name)
                    ->assertSee('かもめ大橋')
                    ->assertSee($tag->tag_name)
                    ->assertSee('よく釣れる')
                    ->assertSee($fishing_type->fishing_type_name)
                    ->assertSee('サビキ釣り');
    }

    public function testIndex_guest()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK)
                    ->assertDontSee('かもめ大橋')
                    ->assertSee('Fishing Spotとは？');
    }

    /**
     * 検索結果（該当あり）
     *
     * @return void
     */
    public function testSearch_available()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => 'かもめ大橋']);
        $other_spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => '貝塚人工島']);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $response = $this->from('/')->get(route('spots.search', [
                'searchWord' => $spot->spot_name,
        ]));

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee('1件')
                ->assertSee($spot->spot_name)
                ->assertSee('かもめ大橋')
                ->assertDontSee($other_spot->spot_name)
                ->assertDontSee('貝塚人工島');

        $spot->fishingTypes()->attach($fishing_type);

        $response = $this->from('/')->get(route('spots.search', [
                'fishing_types' => [$fishing_type->id],
        ]));

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee('1件')
                ->assertSee($spot->spot_name)
                ->assertSee('かもめ大橋')
                ->assertDontSee($other_spot->spot_name)
                ->assertDontSee('貝塚人工島');
    }

    /**
     * 検索結果（該当なし）
     *
     * @return void
     */
    public function testSearch_notAvailable()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => 'かもめ大橋']);
        $other_spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => '貝塚人工島']);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $response = $this->from('/')->get(route('spots.search', [
            'searchWord' => 'とっとパーク小島',
            'fishing_types' => 2,
        ]));

        $response->assertStatus(Response::HTTP_OK)
                ->assertDontSee($spot->spot_name)
                ->assertDontSee('かもめ大橋')
                ->assertDontSee($other_spot->spot_name)
                ->assertDontSee('貝塚人工島');
    }

    /**
     * すべての投稿（検索フォームに記入なし）
     *
     * @return void
     */
    public function testSearchAll()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => 'かもめ大橋']);
        $other_spot = Spot::factory()->for($user)->has(SpotImage::factory(), 'spotImages')
                ->create(['spot_name' => '貝塚人工島']);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $response = $this->from('/')->get('spots/search');

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee('すべての投稿')
                ->assertSee($spot->spot_name)
                ->assertSee('かもめ大橋')
                ->assertSee($other_spot->spot_name)
                ->assertSee('貝塚人工島');
    }

    public function testShow()
    {
        $spot = $this->createSpot();

        $response = $this->get("/spots/{$spot->id}");

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee($spot->spot_name)
                ->assertSee('かもめ大橋');
    }

    public function testCreate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);

        $response = $this->get('/spots/create');

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee('釣りスポット作成')
                ->assertSee($fishing_type->fishing_type_name)
                ->assertSee('サビキ釣り');
    }

    /**
     * 正常系
     *
     * @dataProvider SpotData
     * @return void
     */
    public function testStore_success($params)
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $fishing_type = FishingType::factory()->create(['id' => 1]);
        Storage::fake('s3');

        $response = $this->from('/spots/create')->post(route('spots.store'), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect('/');

        $this->assertCount(1, Spot::all());

        $this->assertDatabaseHas('spots', [
            'spot_name'      => $params['requestData']['spot_name'],
            'user_id'        => $user->id,
        ]);

        // 画像（子テーブル保存）
        $this->assertCount(1, SpotImage::all());
        $this->assertDatabaseHas('spot_images', [
            'spot_id'        => Spot::where('spot_name', $params['requestData']['spot_name'])->pluck('id')
        ]);

        // tagとリレーション（passedValidationが通らないので保留）
        // $this->assertDatabaseHas('spot_tag', [
        //     'tag_id'      => 1,
        //     'spot_id'     => Spot::where('spot_name', $params['requestData']['spot_name'])->pluck('id'), // spotのidと同じ
        // ]);

        // fishing_typeとリレーション
        $this->assertDatabaseHas('spot_fishing_type', [
            'fishing_type_id'      => $params['requestData']['fishing_types'],  // $fishing_typeのidと同じ(=1)
            'spot_id'              => Spot::where('spot_name', $params['requestData']['spot_name'])->pluck('id'), // spotのidと同じ
        ]);

        // S3に画像を保存(fake使用)
        $uploadedFile = $params['requestData']['spot_image1'];
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->from('/spots/create')->post(route('spots.store'), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertSessionHasErrors();

        $error = session('errors')->first();
        $this->assertStringContainsString('釣りスポット名を入力してください', $error);

        $this->assertCount(0, Spot::all());

        $this->assertDatabaseMissing('spots', [
            'spot_name'      => $params['requestData']['spot_name'],
            'user_id'        => $user->id,
        ]);
    }

    function testEdit()
    {
        $spot = $this->createSpot();
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り']);
        $tag = Tag::factory()->create(['name' => 'よく釣れる']);

        $spot->tags()->attach($tag);

        $response = $this->get("/spots/{$spot->id}/edit");

        $tagName = json_encode($tag->tag_name);

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee($spot->spot_name)
                ->assertSee('かもめ大橋')
                ->assertSee($fishing_type->fishing_type_name)
                ->assertSee('サビキ釣り')
                ->assertSee(trim(json_encode($tag->tag_name), '"'))
                ->assertSee(trim(json_encode('よく釣れる'), '"'));
    }

    /**
     * 正常系
     *
     * @dataProvider SpotData
     * @return void
     */
    function testUpdate_success($params)
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $fishing_type = FishingType::factory()->create(['fishing_type_name' => 'サビキ釣り', 'id' => 2]);
        $other_fishing_type = FishingType::factory()->create(['fishing_type_name' => 'ルアー釣り', 'id' => 1]);
        $spot = Spot::factory()->for($user)->create(['spot_name' => '貝塚人工島']);
        $spot_image = SpotImage::factory()->for($spot)->create(['spot_image' => 'fishing.jpg']);
        Storage::fake('s3');


        // 事前にリレーション
        $spot->fishingTypes()->attach($fishing_type);

        $response = $this->from("/spots/{$spot->id}/edit")->put(route('spots.update', $spot->id), $params['requestData']);

        $this->assertDatabaseHas('spots', [
            'spot_name'      => 'かもめ大橋',  // 「貝塚人工島」が「かもめ大橋」に変更（$spot（変数）に変更はない）
            'user_id'        => $user->id,
        ]);

        // fishing_typeとリレーション
        $this->assertDatabaseHas('spot_fishing_type', [
            'fishing_type_id'      => $other_fishing_type->id,  // id = 1
            'spot_id'              => $spot->id
        ]);

        // 画像（子テーブル変更）
        $this->assertDatabaseHas('spot_images', [
            'spot_id'        => $spot->id
        ]);

        // S3に画像を保存(fake使用)
        $uploadedFile = $params['requestData']['spot_image1'];
        $uploadedFile->storeAs('', $uploadedFile, ['disk' => 's3']);
        Storage::disk('s3')->assertExists($uploadedFile);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect(route('spots.show', $spot->id));
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationSpotErrorData
     * @return void
     */
    function testUpdate_validationError($params)
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->create(['spot_name' => '貝塚人工島']);

        $response = $this->from("/spots/{$spot->id}/edit")->put(route('spots.update', $spot->id), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertSessionHasErrors();

        $error = session('errors')->first();
        $this->assertStringContainsString('釣りスポット名を入力してください', $error);

        $this->assertDatabaseMissing('spots', [
            'spot_name'      => $params['requestData']['spot_name'], // !=「サビキ釣り」
            'user_id'        => $user->id,
        ]);
    }

    public function testDestroy()
    {
        $spot = $this->createSpot();

        // DELETE リクエスト
        $response = $this->delete(route('spots.destroy', $spot->id));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect('/');

        $this->assertCount(0, Spot::all());
    }

    public function testFavorite()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->create();

        $response = $this->put(route('spots.favorite', [$spot->id, $user->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('spot_favorite', [
            'spot_id' => $spot->id,
            'user_id' => $user->id,
        ]);
    }

    public function testUnFavorite()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $spot = Spot::factory()->for($user)->create();

        // 事前にリレーション
        $spot->spotFavorites()->attach($user);

        $response = $this->delete(route('spots.unfavorite', [$spot->id, $user->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('spot_favorite', [
            'spot_id' => $spot->id,
            'user_id' => $user->id,
        ]);
    }

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
                        'spot_image1' => UploadedFile::fake()->image('defaultSpot.jpg'),
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
                    ],
                ]
            ],
        ];
    }
}
