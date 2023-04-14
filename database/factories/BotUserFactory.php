<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\User;

class BotUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotUser::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'user_id' => User::factory(),
            'parent_id' => User::factory(),
            'is_admin' => $this->faker->boolean,
            'is_work' => $this->faker->boolean,
            'user_in_location' => $this->faker->boolean,
        ];
    }
}
