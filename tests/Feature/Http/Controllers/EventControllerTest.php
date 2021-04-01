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
    }

    public function testIndex()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['fishing_type' => 'サビキ釣り']);

        $response = $this->get(route('events', $user));

        $response->assertStatus(Response::HTTP_OK)
                    ->assertSee($event->fishing_type)
                    ->assertSee('サビキ釣り')
                    ->assertSee('釣りを記録しよう');
    }

    public function testSetEvents()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['fishing_type' => 'サビキ釣り']);

        $response = $this->get(route('setEvents', [
            $user,
            'start' => '2021-04-01',
            'end' => '2021-04-30'
        ]));

        $response->assertStatus(Response::HTTP_OK);
    }

    function testEditEventDate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['date' => '2021-03-31']);

        $response = $this->from(route('events', $user))->put(route('editEventDate', [$event, $user]), [
            'id' => $event->id,
            'newDate' => '2021-04-01'
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('events', [
            'date'        => '2021-04-01',
        ]);
    }

    /**
     * 正常系
     *
     * @dataProvider EventData
     * @return void
     */
    public function testAddEvent_success($params)
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->from(route('events', $user))->post(route('addEvent', $user), $params['requestData']);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(1, Event::all());

        $this->assertDatabaseHas('events', [
            'fishing_type'      => $params['requestData']['fishing_type'],
            'user_id'        => $user->id,
        ]);
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationEventErrorData
     * @return void
     */
    public function testAddEvent_validationError($params)
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->from(route('events', $user))->post(route('addEvent', $user), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertSessionHasErrors();

        $error = session('errors')->first();
        $this->assertStringContainsString('月日を入力してください', $error);

        $this->assertCount(0, Event::all());

        $this->assertDatabaseMissing('events', [
            'fishing_type'      => $params['requestData']['fishing_type'],
            'user_id'        => $user->id,
        ]);
    }

    function testEdit()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['fishing_type' => 'サビキ釣り']);

        $response = $this->get(route('editEvent', [$user, $event]));

        $response->assertStatus(Response::HTTP_OK)
                ->assertSee($event->fishing_type)
                ->assertSee('サビキ釣り');
    }

    /**
     * 正常系
     *
     * @dataProvider EventData
     * @return void
     */
    function testUpdate_success($params)
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['fishing_type' => 'ルアー釣り']);

        $response = $this->from(route('editEvent', [$event, $user]))->put(route('events.update', [$user, $event]), $params['requestData']);

        $this->assertDatabaseHas('events', [
            'fishing_type'      => 'サビキ釣り',  // 「ルアー釣り」が「サビキ釣り」に変更
            'user_id'        => $user->id,
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect(route('events', $user));
    }

    /**
     * 異常系: バリデーションに引っかかる
     *
     * @dataProvider validationEventErrorData
     * @return void
     */
    function testUpdate_validationError($params)
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['fishing_type' => 'ルアー釣り']);

        $response = $this->from(route('editEvent', [$user, $event]))->put(route('events.update', [$user, $event]), $params['requestData']);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors();

        $error = session('errors')->first();
        $this->assertStringContainsString('月日を入力してください', $error);

        $this->assertDatabaseMissing('events', [
            'fishing_type'      => 'サビキ釣り',
            'user_id'        => $user->id,
        ]);
    }

    public function testDeleteEvent()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['fishing_type' => 'ルアー釣り']);

        $response = $this->delete(route('deleteEvent', [$user, $event]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(0, Event::all());
    }

    public function EventData()
    {
        return [
            'valid data' => [
                [
                    'requestData' => [
                        'date' => '2021-03-30',
                        'fishing_type' => 'サビキ釣り',
                        'spot' => 'かもめ大橋',
                        'bait' => 'アミエビ',
                        'weather' => '晴れ',
                        'fishing_start_time' => '07:10',
                        'fishing_end_time' => '17:50',
                        'detail' => 'アジが釣れた。',
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
                        'date' => null,
                        'fishing_type' => 'サビキ釣り',
                        'spot' => 'かもめ大橋',
                        'bait' => 'アミエビ',
                        'weather' => '晴れ',
                        'fishing_start_time' => '07:10',
                        'fishing_end_time' => '17:50',
                        'detail' => 'アジが釣れた。',
                    ],
                ]
            ],
        ];
    }
}
