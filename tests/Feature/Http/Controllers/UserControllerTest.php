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


class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();
    }

    // public function testShow()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $response = $this->get("/users/{$user->id}");

    //     $response->assertStatus(Response::HTTP_OK)
    //             ->assertSee($user->user_name)
    //             ->assertSee('ゲスト');
    // }

    public function testCreate()
    {
        $response = $this->get('/signup');

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee('新規登録');
    }

    /**
     * 正常系
     *
     * @dataProvider UserData
     * @return void
     */
    public function testStore_success($params)
    {
        $response = $this->from('/signup')->post(route('users.store'), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect('/');

        $this->assertCount(1, Spot::all());

        $this->assertDatabaseHas('spots', [
            'id'             => 1,
            'spot_name'      => $params['requestData']['spot_name'],
            'user_id'        => $user->id,
        ]);
    }

    // /**
    //  * 異常系: バリデーションに引っかかる
    //  *
    //  * @dataProvider validationUserErrorData
    //  * @return void
    //  */
    // public function testStore_validationError($params)
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $response = $this->from('/spots/create')->post(route('spots.store'), $params['requestData']);

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

    //     $response = $this->from("/spots/{$spot->id}/edit")->put(route('spots.update', $spot->id), $params['requestData']);

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

         // S3に画像を保存(fake使用)
        //  Storage::fake('s3');
        //  $uploadedFile = UploadedFile::fake()->image($params['requestData']['spot_image']);
        //  $uploadedFile->storeAs('', $params['requestData']['spot_image'], ['disk' => 's3']);
        //  Storage::disk('s3')->assertExists('defaultSpot.jpg');
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

    public function UserData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'user_name' => 'ゲスト',
                        'email' => 'guest@example.com',
                        'introduction' => 'よろしくお願いいたします。',
                        'password' => 'guest123',
                        'password_confirm' => 'guest123',
                    ],
                ]
            ]
        ];
    }

    public function validationUserErrorData()
    {
        return [
            // user_nameのバリデーションのみ
            'validation: ユーザー名を入力してください' => [
                [
                    'requestData' => [
                        'user_name' => null,
                        'email' => 'guest@example.com',
                        'introduction' => 'よろしくお願いいたします。',
                        'password' => 'guest123',
                        'password_confirm' => 'guest123',
                    ],
                ]
            ],
        ];
    }
}
