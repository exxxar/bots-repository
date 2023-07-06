<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\User;

class ActionStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActionStatus::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bot_id' => Bot::factory(),
            'script' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'max_attempts' => $this->faker->numberBetween(-10000, 10000),
            'current_attempts' => $this->faker->numberBetween(-10000, 10000),
            'completed_at' => $this->faker->dateTime(),
            'data' => '{}',
        ];
    }
}
