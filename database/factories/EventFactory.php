<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => '2021-03-30',
            'fishing_type' => 'サビキ釣り',
            'spot' => 'かもめ大橋',
            'fishing_start_time' => '07:10',
            'fishing_end_time' => '17:50',
            'detail' => 'アジが釣れた。',
        ];
    }
}
