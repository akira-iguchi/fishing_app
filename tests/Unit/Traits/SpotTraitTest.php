<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Spot;
use App\Models\User;
use App\Models\SpotImage;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpotTraitTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * 3つの画像選択で、どの選択でも保存できる（一部だけテスト）
     *
     * @return void
     */
    public function testImageUploadByCase()
    {
        Storage::fake('s3');

        $response = $this->from(route('spots.create', $this->user))
            ->json('POST', route('spots.store'), [
                'spot_name'           => 'かもめ大橋',
                'explanation'         => 'テスト',
                'latitude'            => 34.23,
                'longitude'           => 135.63,
                'spot_image_first'    => UploadedFile::fake()->image('defaultSpot.jpg'),
                'spot_image_second'   => UploadedFile::fake()->image('defaultSpot.jpg'),
            ]);

        $this->assertCount(2, SpotImage::all());

        Spot::query()->delete();

        $response = $this->from(route('spots.create', $this->user))
            ->json('POST', route('spots.store'), [
                'spot_name'           => 'かもめ大橋',
                'explanation'         => 'テスト',
                'latitude'            => 34.23,
                'longitude'           => 135.63,
                'spot_image_first'    => UploadedFile::fake()->image('defaultSpot.jpg'),
                'spot_image_third'   => UploadedFile::fake()->image('defaultSpot.jpg'),
            ]);

        $this->assertCount(2, SpotImage::all());

        Spot::query()->delete();

        $response = $this->from(route('spots.create', $this->user))
            ->json('POST', route('spots.store'), [
                'spot_name'           => 'かもめ大橋',
                'explanation'         => 'テスト',
                'latitude'            => 34.23,
                'longitude'           => 135.63,
                'spot_image_third'   => UploadedFile::fake()->image('defaultSpot.jpg'),
            ]);

        $this->assertCount(1, SpotImage::all());
    }
}