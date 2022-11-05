<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_name' => 'ゲスト',
            'email' => 'guest@example.com',
            'user_image' => 'defaultUser.jpg',
            'password' => 'password',
            'remember_token' => Str::random(10),
            'introduction' => 'よろしくお願いします！',
        ];
    }
}
