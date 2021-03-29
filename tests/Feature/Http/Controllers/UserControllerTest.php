<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
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

    // public function testCreate()
    // {
    //     $response = $this->get('/signup');

    //     $response->assertStatus(Response::HTTP_OK)
    //             ->assertSee('新規登録');
    // }

    // /**
    //  * 正常系
    //  *
    //  * @dataProvider UserData
    //  * @return void
    //  */
    // public function testRegister_success($params)
    // {
    //     $response = $this->from('/signup')->post(route('signup.post'), $params['requestData']);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertRedirect('/');

    //     $this->assertCount(1, User::all());

    //     $this->assertDatabaseHas('users', [
    //         'id'             => 1,
    //         'user_name'      => $params['requestData']['user_name'],
    //     ]);

    //     // S3に画像を保存(fake使用)
    //     Storage::fake('s3');
    //     $uploadedFile = UploadedFile::fake()->image($params['requestData']['user_image']);
    //     $uploadedFile->storeAs('', $params['requestData']['user_image'], ['disk' => 's3']);
    //     Storage::disk('s3')->assertExists('defaultUser.jpg');
    // }

    // /**
    //  * 異常系: バリデーションに引っかかる
    //  *
    //  * @dataProvider validationUserErrorData
    //  * @return void
    //  */
    // public function testRegister_validationError($params)
    // {
    //     $response = $this->from('/signup')->post(route('signup.post'), $params['requestData']);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertRedirect('/signup')
    //             ->assertSessionHasErrors();

    //     $error = session('errors')->first();
    //     $this->assertStringContainsString('ユーザー名を入力してください', $error);

    //     $this->assertCount(0, User::all());

    //     $this->assertDatabaseMissing('users', [
    //         'id'             => 1,
    //         'user_name'      => $params['requestData']['user_name'],
    //     ]);
    // }

    // /**
    //  * ゲストユーザー以外（id > 1）はアクセスできる
    //  *
    //  * @return void
    //  */
    // function testEditCanAccess()
    // {
    //     $user = User::factory()->create(['id' => 2]);
    //     $this->actingAs($user);

    //     $response = $this->get("/users/{$user->id}/edit");

    //     $response->assertStatus(Response::HTTP_OK)
    //             ->assertSee($user->user_name)
    //             ->assertSee('ゲスト');
    // }

    // /**
    //  * ゲストユーザー（id = 1）はアクセスできない
    //  *
    //  * @return void
    //  */
    // function testEditCannotAccess()
    // {
    //     $user = User::factory()->create(['id' => 1]);
    //     $this->actingAs($user);

    //     $response = $this->get("/users/{$user->id}/edit");

    //     $response->assertStatus(Response::HTTP_FOUND)
    //             ->assertRedirect('/')
    //             ->assertDontSee($user->user_name)
    //             ->assertDontSee('ゲスト');
    // }

    /**
     * 正常系
     *
     * @dataProvider UpdateUserData
     * @return void
     */
    function testUpdate($params)
    {
        $user = User::factory()->create(['user_name' => 'テスト']);
        $this->actingAs($user);

        $response = $this->from("/users/{$user->id}/edit")->put(route('users.update', $user->id), $params['requestData']);

        $this->assertDatabaseHas('users', [
            'user_name'      => 'ゲスト',  // 「テスト」が「ゲスト」に変更（$user（変数）に変更はない）
            'email' => 'guest@example.com',
            'user_image' => 'defaultAvatar.jpg',
            'introduction' => 'よろしくお願いいたします!!!',
            'password' => 'guest123',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect("/users/{$user->id}");

        // S3に画像を保存(fake使用)
        Storage::fake('s3');
        $uploadedFile = UploadedFile::fake()->image($params['requestData']['user_image']);
        $uploadedFile->storeAs('', $params['requestData']['user_image'], ['disk' => 's3']);
        Storage::disk('s3')->assertExists('defaultAvatar.jpg');
    }

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
                        'user_image' => 'defaultUser.jpg',
                        'introduction' => 'よろしくお願いいたします。',
                        'password' => 'guest123',
                        'password_confirmation' => 'guest123',
                    ],
                ]
            ]
        ];
    }

    public function UpdateUserData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'user_name' => 'ゲスト',
                        'email' => 'guest@example.com',
                        'user_image' => 'defaultAvatar.jpg',
                        'introduction' => 'よろしくお願いいたします!!!',
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
                        'user_image' => 'defaultUser.jpg',
                        'introduction' => 'よろしくお願いいたします。',
                        'password' => 'guest123',
                        'password_confirmation' => 'guest123',
                    ],
                ]
            ],
        ];
    }
}
