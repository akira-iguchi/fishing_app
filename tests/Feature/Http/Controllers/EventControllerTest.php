<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\Factories\Traits\CreateEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;


class EventControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateEvent;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testIndex()
    {
        $event = Event::factory()->for($this->user)->create();

        $response = $this->json('GET', route('events.index', $this->user));

        $response->assertStatus(200)
            ->assertJson([
                ['user_name' => $this->user->user_name],
                [['start' => $event->date, 'title' => $event->spot]]  // イベントの開始日
            ]);
    }

    /**
     * 正常系
     *
     * @dataProvider EventData
     * @return void
     */
    public function testStore_success($params)
    {

        $response = $this->from(route('events.index', $this->user))
            ->json('POST', route('events.store', $this->user), $params['requestData']);

        $response->assertStatus(201);

        $this->assertCount(1, Event::all());

        $this->assertDatabaseHas('events', [
            'fishing_type'      => $params['requestData']['fishing_type'],
            'user_id'        => $this->user->id,
        ]);
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationEventErrorData
     * @return void
     */
    public function testStore_validationError($params)
    {

        $response = $this->from(route('events.index', $this->user))
            ->json('POST', route('events.store', $this->user), $params['requestData']);

        $response->assertStatus(422);

        $error = $response['errors']['date'][0];
        $this->assertEquals('月日を入力してください', $error);

        $this->assertCount(0, Event::all());

        $this->assertDatabaseMissing('events', [
            'fishing_type'      => $params['requestData']['fishing_type'],
            'user_id'        => $this->user->id,
        ]);
    }

    function testEditEventDate()
    {
        $event = Event::factory()->for($this->user)->create(['date' => '2021-03-31']);

        $response = $this->from(route('events.index', $this->user))->json('PUT', route('editEventDate', [$event, $this->user]), [
            'id' => $event->id,
            'newDate' => '2021-04-01'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('events', [
            'date'        => '2021-04-01',
        ]);
    }

    function testEdit()
    {
        $event = Event::factory()->for($this->user)->create(['fishing_type' => 'サビキ釣り']);

        $response = $this->json('GET', route('events.edit', [$this->user, $event]));

        $response->assertStatus(200)
            ->assertJson([
                ['user_name' => $this->user->user_name],
                ['spot' => $event->spot],
                [['start' => $event->date, 'title' => $event->spot]]  // イベントの開始日
            ]);
    }

    /**
     * 正常系
     *
     * @dataProvider EventData
     * @return void
     */
    function testUpdate_success($params)
    {
        $event = Event::factory()->for($this->user)->create(['fishing_type' => 'ルアー釣り']);

        $response = $this->from(route('events.edit', [$event, $this->user]))
            ->json('PUT', route('events.update', [$this->user, $event]), $params['requestData']);

        $this->assertDatabaseHas('events', [
            'fishing_type'      => 'サビキ釣り',  // 「ルアー釣り」が「サビキ釣り」に変更
            'user_id'        => $this->user->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'spot' => $params['requestData']['spot']
            ]);
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationEventErrorData
     * @return void
     */
    function testUpdate_validationError($params)
    {
        $event = Event::factory()->for($this->user)->create(['fishing_type' => 'ルアー釣り']);

        $response = $this->from(route('events.edit', [$this->user, $event]))
            ->json('PUT', route('events.update', [$this->user, $event]), $params['requestData']);

        $response->assertStatus(422);

        $error = $response['errors']['date'][0];
        $this->assertEquals('月日を入力してください', $error);

        $this->assertDatabaseMissing('events', [
            'fishing_type'      => 'サビキ釣り',
            'user_id'        => $this->user->id,
        ]);
    }

    public function testDestroy()
    {
        $event = Event::factory()->for($this->user)->create();

        $response = $this->json('DELETE', route('events.destroy', [$this->user, $event]));

        $response->assertStatus(200);

        $this->assertCount(0, Event::all());
    }

    public function EventData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'date'               => '2021-03-30',
                        'fishing_type'       => 'サビキ釣り',
                        'spot'               => 'かもめ大橋',
                        'fishing_start_time' => '07:10',
                        'fishing_end_time'   => '17:50',
                        'detail'             => 'アジが釣れた。',
                    ],
                ]
            ]
        ];
    }

    public function validationEventErrorData()
    {
        return [
            // dateのバリデーションのみ
            'validation: 月日を入力してください' => [
                [
                    'requestData' => [
                        'date'               => null,
                        'fishing_type'       => 'サビキ釣り',
                        'spot'               => 'かもめ大橋',
                        'fishing_start_time' => '07:10',
                        'fishing_end_time'   => '17:50',
                        'detail'             => 'アジが釣れた。',
                    ],
                ]
            ],
        ];
    }
}
