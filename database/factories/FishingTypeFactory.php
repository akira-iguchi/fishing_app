<?php

namespace Database\Factories;

use App\Models\FishingType;
use Illuminate\Database\Eloquent\Factories\Factory;

class FishingTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FishingType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fishing_type_name' => 'サビキ釣り',
            'fishing_type_image' => 'defaultSpot.jpg',
            'content' => '「サビキ」と呼ばれる６本以上の擬似餌バリで釣る方法。',
        ];
    }
}
