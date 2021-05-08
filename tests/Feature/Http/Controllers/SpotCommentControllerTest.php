<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotComment;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;


class SpotCommentControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * 正常系
     *
     * @dataProvider SpotCommentData
     * @return void
     */
    public function testStore_success($params)
    {
        $spot = Spot::factory()->for($this->user)->create();

        Storage::fake('s3');
        $response = $this->from(route('spots.show', $spot))
            ->json('POST', route('spots.comment', $spot), $params['requestData']);

        $response->assertStatus(201);

        $this->assertCount(1, SpotComment::all());

        $this->assertDatabaseHas('spot_comments', [
            'spot_id'      => $spot->id,
            'user_id'        => $this->user->id,
            'comment'      => $params['requestData']['comment'],
        ]);

        // S3に画像を保存(fake使用)
        $uploadedFile = $params['requestData']['comment_image'];
        $uploadedFile->storeAs('', $uploadedFile, ['disk' => 's3']);
        Storage::disk('s3')->assertExists($uploadedFile);
    }

    /**
     * 異常系: バリデーションに引っかかる
    *
    * @dataProvider validationSpotCommentErrorData
    * @return void
    */
    public function testStore_validationError($params)
    {
        $spot = Spot::factory()->for($this->user)->create();

        $response = $this->from(route('spots.show', $spot))
            ->json('POST', route('spots.comment', $spot), $params['requestData']);

        $response->assertStatus(422);

        $error = $response['errors']['comment'][0];
        $this->assertEquals('コメントを入力してください', $error);

        $this->assertCount(0, SpotComment::all());

        $this->assertDatabaseMissing('spot_comments', [
            'spot_id'      => $spot->id,
            'user_id'        => $this->user->id,
            'comment'      => $params['requestData']['comment'],
        ]);
    }

    public function testDestroy()
    {
        $spot = Spot::factory()->for($this->user)->create();

        $comment = SpotComment::factory()
            ->for($this->user)->for($spot)->create();

        $response = $this->json('DELETE', route('comment.delete', [$spot, $comment]));

        $response->assertStatus(200);

        $this->assertCount(0, SpotComment::all());
    }

    public function SpotCommentData()
    {
        return [
            'valid data' => [
                [
                    'requestData'       => [
                        'spot_id'       => 1,
                        'comment'       => 'たくさん釣れた！',
                        'comment_image' => UploadedFile::fake()->image('default.jpg'),
                    ],
                ]
            ]
        ];
    }

    public function validationSpotCommentErrorData()
    {
        return [
            // spot_nameのバリデーションのみ
            'validation: コメントを入力してください' => [
                [
                    'requestData' => [
                        'comment' => null,
                    ],
                ]
            ],
        ];
    }
}
