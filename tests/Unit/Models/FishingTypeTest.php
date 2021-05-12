<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\FishingType;
use Tests\Factories\Traits\CreateSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FishingTypeTest extends TestCase
{
    use RefreshDatabase;
    use CreateSpot;

    public function setUp(): void
    {
        parent::setUp();

        $this->spot = $this->createSpot();

        $this->fishingType = FishingType::factory()->create();
    }

    public function testFactoryable()
    {
        $eloquent = app(FishingType::class);
        $this->assertNotEmpty($eloquent->get());
    }

    public function testFishingTypeBelongsToManySpots()
    {
        $this->fishingType->spots()->attach($this->spot);
        // refresh() で再度同じレコードを取得しなおし、リレーション先の件数が作成した件数と一致することを確認し、リレーションが問題ないことを保証
        $this->assertEquals(1, count($this->fishingType->refresh()->spots));
    }
}
