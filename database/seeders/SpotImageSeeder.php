<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpotImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('spot_images')->insert([
            [
                'id' => 1,
                'spot_id' => 1,
                'spot_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/spot/defaultSpot.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 2,
                'spot_id' => 2,
                'spot_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/spot/defaultSpot.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 3,
                'spot_id' => 3,
                'spot_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/spot/defaultSpot.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 4,
                'spot_id' => 4,
                'spot_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/spot/defaultSpot.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 5,
                'spot_id' => 5,
                'spot_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/spot/defaultSpot.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 6,
                'spot_id' => 6,
                'spot_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/spot/defaultSpot.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}
