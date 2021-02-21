<?php

namespace Database\Factories;

use App\Models\Spot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SpotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Spot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'かもめ大橋',
            'explanation' => 'テスト',
            'address' => '住之江区',
            'latitude' => 34.23,
            'longitude' => 135.63,
        ];
    }
}
