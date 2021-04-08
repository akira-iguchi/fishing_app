<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
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

    /**
     * ゲストユーザー以外（id > 1）はアクセスできる
     *
     * @return void
     */
    function testEditCanAccess()
    {
        $user = User::factory()->create(['id' => 2]);
        $this->actingAs($user);

        $response = $this->get("/users/{$user->id}/edit");

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee($user->user_name)
                ->assertSee('ゲスト');
    }

    /**
     * ゲストユーザー（id = 1）はアクセスできない
     *
     * @return void
     */
    function testEditCannotAccess()
    {
        $user = User::factory()->create(['id' => 1]);
        $this->actingAs($user);

        $response = $this->get("/users/{$user->id}/edit");

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect('/')
                ->assertDontSee($user->user_name)
                ->assertDontSee('ゲスト');
    }

    /**
     * 正常系
     *
     * @dataProvider UserData
     * @return void
     */
    function testUpdate_success($params)
    {
        $user = User::factory()->create(['user_name' => 'テスト']);
        $this->actingAs($user);

        Storage::fake('s3');
        $response = $this->from("/users/{$user->id}/edit")->put(route('users.update', $user->id), $params['requestData']);

        $this->assertDatabaseHas('users', [
            'user_name'      => 'ゲスト',  // 「テスト」が「ゲスト」に変更（$user（変数）に変更はない）
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect("/users/{$user->id}");

        // S3に画像を保存(fake使用)
        $uploadedFile = $params['requestData']['user_image'];
        $uploadedFile->storeAs('', $uploadedFile, ['disk' => 's3']);
        Storage::disk('s3')->assertExists($uploadedFile);
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationUserErrorData
     * @return void
     */
    function testUpdate_validationError($params)
    {
        $user = User::factory()->create(['user_name' => 'テスト']);
        $this->actingAs($user);

        $response = $this->from("/users/{$user->id}/edit")->put(route('users.update', $user->id), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect("/users/{$user->id}/edit")
                ->assertSessionHasErrors();

        $error = session('errors')->first();
        $this->assertStringContainsString('ユーザー名を入力してください', $error);

        $this->assertDatabaseMissing('users', [
            'user_name'      => 'ゲスト',
        ]);
    }

    public function testFollow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $other_user = User::factory()->create();
        $this->actingAs($other_user);

        $response = $this->put(route('users.follow', [$user->id, $other_user->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('follows', [
            'followee_id' => $user->id,
            'follower_id' => $other_user->id,
        ]);
    }

    /**
     * 自信をフォローはできない
     *
     * @return void
     */
    public function testFollow_myself()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('users.follow', [$user->id, $user->id]));

        $response->assertStatus(404);

        $this->assertDatabaseMissing('follows', [
            'followee_id' => $user->id,
            'follower_id' => $user->id,
        ]);
    }

    public function testUnFollow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $other_user = User::factory()->create();
        $this->actingAs($other_user);

        // 事前にリレーション
        $user->followings()->attach($other_user);

        $response = $this->delete(route('users.unfollow', [$user->id, $other_user->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('follows', [
            'followee_id' => $user->id,
            'follower_id' => $other_user->id,
        ]);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get("/users/{$user->id}");

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee($user->user_name)
                ->assertSee('ゲスト');
    }

    public function UserData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'user_name' => 'ゲスト',
                        'email' => 'guest@example.com',
                        'user_image' => UploadedFile::fake()->image('defaultAvatar.jpg'),
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
                    ],
                ]
            ],
        ];
    }
}
