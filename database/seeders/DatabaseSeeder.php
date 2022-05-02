<?php

namespace Database\Seeders;

use App\Models\FishingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call([
			UserSeeder::class,
			SpotSeeder::class,
			SpotImageSeeder::class,
			FishingTypeSeeder::class,
			TagSeeder::class
		]);
	}
}
