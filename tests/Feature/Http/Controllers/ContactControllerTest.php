<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Mail\ContactSendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->json('GET', route('contact.index'));

        $response->assertStatus(200);
    }

    /**
     * 正常系
     *
     * @dataProvider ContactData
     * @return void
     */
    public function testConfirm_success($params)
    {
        $response = $this->from(route('contact.index'))
            ->json('POST', route('contact.confirm'), $params['requestData']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'email' => 'guest@example.com',
                'title' => 'テスト',
                'body' => '問題が発生しました。',
            ]);
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationContactErrorData
     * @return void
     */
    public function testConfirm_validationError($params)
    {
        $response = $this->from(route('contact.index'))
            ->json('POST', route('contact.confirm'), $params['requestData']);

        $response->assertStatus(422);

        $error = $response['errors']['email'][0];
        $this->assertEquals('メールアドレスを入力してください。', $error);
    }

    public function testConfirmPage()
    {
        $response = $this->json('GET', route('contact.confirmPage'));

        $response->assertStatus(200);
    }

    /**
     * メール送信
     *
     * @dataProvider ContactData
     * @return void
     */
    public function testSend($params)
    {
        Mail::fake();

        $response = $this->from(route('contact.confirmPage'))
            ->json('POST', route('contact.send'), $params['requestData']);

        // github actionsで通らないため保留
        // 1回送信されたことをアサート
        Mail::assertSent(ContactSendMail::class, 1);

        // 製作者のメールアドレス
        $email = config('mail.mailers.smtp.username');

        // メールが製作者に送信されていることをアサート
        Mail::assertSent(
            ContactSendMail::class,
            function ($mail) use ($email) {
                return $mail->to[0]['address'] === $email;
            }
        );
    }

    public function testThanks()
    {
        $response = $this->json('GET', route('contact.thanks'));

        $response->assertStatus(200);
    }

    public function ContactData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'email'       => 'guest@example.com',
                        'title'       => 'テスト',
                        'body'        => '問題が発生しました。',
                    ],
                ]
            ]
        ];
    }

    public function validationContactErrorData()
    {
        return [
            // emailのバリデーションのみ
            'validation: メールアドレスを入力してください' => [
                [
                    'requestData' => [
                        'email'       => '',
                        'title'       => 'テスト',
                        'body'        => '問題が発生しました。',
                    ],
                ]
            ],
        ];
    }
}
