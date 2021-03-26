<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\Spot;
use App\Models\User;
use App\Models\FishingType;
use Tests\TestCase;

class SpotControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $response = $this->dummyLogin();
    }

    public function testIndex()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create(['spot_name' => 'かもめ大橋']);

        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertSee($spot->spot_name);
        $response->assertSee('かもめ大橋');
    }

    // public function testShow()
    // {
    //     $user = User::factory()->create();
    //     $spot = Spot::factory()->for($user)->create();
    //     // GET リクエスト
    //     $response = $this->get(route('spots.show', [$spot->id]));

    //     // レスポンスの検証
    //     $response->assertOk();
    // }

    // public function testCreate()
    // {
    //     // GET リクエスト
    //     $response = $this->get(route('spots.create'));

    //     $response->assertOk();
    // }

    // public function testStore()
    // {
    //     $data = [
    //         'spots' => [
    //             'spot_name' => 'かもめ大橋',
    //             'explanation' => 'テスト',
    //             'address' => '住之江区',
    //             'latitude' => 34.23,
    //             'longitude' => 135.63,
    //             'user_id' => 1,
    //         ],
    //     ];

    //     // POST リクエスト
    //     $response = $this->post(route('spots.store'), $data);

    //     $response->assertStatus(302);

    //     $response->assertRedirect('/');

    //     $this->assertCount(0, Spot::all());
    // }

    // public function testDestroy()
    // {
    //     $user = User::factory()->create();
    //     $spot = Spot::factory()->for($user)->create();

    //     // DELETE リクエスト
    //     $response = $this->delete(route('spots.destroy', $spot->id));

    //     $response->assertStatus(302);

    //     $response->assertRedirect('/');

    //     // `spots` テーブルが1件になっている
    //     $this->assertCount(0, Spot::all());
    // }

    /**
     * ダミーユーザーログイン
     */
    private function dummyLogin()
    {
        $user = User::factory()->create();
        return $this->actingAs($user)
                    ->withSession(['user_id' => $user->id])
                    ->get('/');
    }
}
