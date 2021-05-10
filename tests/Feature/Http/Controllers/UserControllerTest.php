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

        $this->user = User::factory()
            ->create(['id' => 2, 'user_name' => 'テストユーザー']);
        $this->actingAs($this->user);
    }

    public function testShow()
    {
        $response = $this->json('GET', route('users.show', $this->user));

        $response->assertStatus(200)
            ->assertJson(
                ['user_name' => $this->user->user_name],
            );
    }

    /**
     * ゲストユーザー以外（id > 1）はアクセスできる（ゲストユーザーのアクセス不可はVue.jsで処理）
     *
     * @return void
     */
    function testEditCanAccess()
    {
        $response = $this->json('GET', route('users.edit', $this->user));

        $response->assertStatus(200)
            ->assertJson(
                ['user_name' => $this->user->user_name],
            );
    }

    /**
     * 正常系
     *
     * @dataProvider UserData
     * @return void
     */
    function testUpdate_success($params)
    {
        Storage::fake('s3');

        $response = $this->from(route('users.edit', $this->user))
            ->json('PUT', route('users.update', $this->user), $params['requestData']);

        $this->assertDatabaseHas('users', [
            'user_name'      => 'ゲストユーザー',  // 「テストユーザー」が「ゲストユーザー」に変更
        ]);

        $response->assertStatus(201)
            ->assertJson(
                ['user_name' => 'ゲストユーザー'],  // 「貝塚人工島」が「かもめ大橋」に変更
            );

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
        $response = $this->from(route('users.edit', $this->user))
            ->json('PUT', route('users.update', $this->user), $params['requestData']);

        $response->assertStatus(422);

        $error = $response['errors']['user_name'][0];
        $this->assertEquals('ユーザー名を入力してください。', $error);

        $this->assertDatabaseMissing('users', [
            'user_name'      => 'ゲストユーザー',
        ]);
    }

    public function UserData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'user_name' => 'ゲストユーザー',
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
            'validation: ユーザー名を入力してください。' => [
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
