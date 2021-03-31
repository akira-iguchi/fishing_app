<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testRegisterView()
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
    public function testRegister_success($params)
    {
        $response = $this->from('/signup')->post(route('signup.post'), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect('/');

        $this->assertCount(1, User::all());

        $this->assertDatabaseHas('users', [
            'id'             => 1,
            'user_name'      => $params['requestData']['user_name'],
        ]);

        // S3に画像を保存(fake使用)
        Storage::fake('s3');
        $uploadedFile = UploadedFile::fake()->image($params['requestData']['user_image']);
        $uploadedFile->storeAs('', $params['requestData']['user_image'], ['disk' => 's3']);
        Storage::disk('s3')->assertExists('defaultUser.jpg');
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationUserErrorData
     * @return void
     */
    public function testRegister_validationError($params)
    {
        $response = $this->from('/signup')->post(route('signup.post'), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect('/signup')
                ->assertSessionHasErrors();

        $error = session('errors')->first();
        $this->assertStringContainsString('ユーザー名を入力してください', $error);

        $this->assertCount(0, User::all());

        $this->assertDatabaseMissing('users', [
            'id'             => 1,
            'user_name'      => $params['requestData']['user_name'],
        ]);
    }

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
