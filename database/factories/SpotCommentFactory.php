<?php

namespace Database\Factories;

use App\Models\SpotComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpotCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpotComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => 'テスト',
        ];
    }
}
