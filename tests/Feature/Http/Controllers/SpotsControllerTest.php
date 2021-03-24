<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Spot;
use App\Models\User;
use Tests\TestCase;

class SpotsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex()
    {
        // GET リクエスト
        $response = $this->get(url('/'));

        // レスポンスの検証
        $response->assertOk();  # ステータスコードが 200
    }

    public function testStore()
    {
        $data = [
            'spot' => [
                'spot_name' => 'かもめ大橋',
                'explanation' => 'テスト',
                'address' => '住之江区',
                'latitude' => 34.23,
                'longitude' => 135.63,
                'user_id' => 1,
            ],
        ];

        // POST リクエスト
        $response = $this->post(route('spots.store'), $data);

        // レスポンス検証
        $response->assertStatus(302);

    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $spot = Spot::factory()->for($user)->create();

        // DELETE リクエスト
        $response = $this->delete(route('spots.destroy', [$spot->id]));

        $response->assertStatus(302);

        // `spots` テーブルが1件になっている
        $this->assertCount(1, Spot::all());
    }
}
