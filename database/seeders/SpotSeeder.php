<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('spots')->insert([
            [
                'user_id' => 1,
                'spot_name' => Str::random(10),
                'explanation' => Str::random(200),
                'address' => Str::random(20),
                'latitude' => 34.32,
                'longitude' => 135.153,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'user_id' => 2,
                'spot_name' => Str::random(10),
                'explanation' => Str::random(200),
                'address' => Str::random(20),
                'latitude' => 34.32,
                'longitude' => 135.153,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'user_id' => 2,
                'spot_name' => Str::random(10),
                'explanation' => Str::random(200),
                'address' => Str::random(20),
                'latitude' => 34.32,
                'longitude' => 135.153,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'user_id' => 2,
                'spot_name' => Str::random(10),
                'explanation' => Str::random(200),
                'address' => Str::random(20),
                'latitude' => 34.32,
                'longitude' => 135.153,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'user_id' => 2,
                'spot_name' => Str::random(10),
                'explanation' => Str::random(200),
                'address' => Str::random(20),
                'latitude' => 34.32,
                'longitude' => 135.153,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'user_id' => 2,
                'spot_name' => Str::random(10),
                'explanation' => Str::random(200),
                'address' => Str::random(20),
                'latitude' => 34.32,
                'longitude' => 135.153,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}
