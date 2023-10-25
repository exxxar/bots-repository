<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotMedia;
use App\Models\BotUser;

class BotMediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotMedia::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'bot_user_id' => BotUser::factory(),
            'rating' => $this->faker->numberBetween(-10000, 10000),
            'file_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'caption' => $this->faker->text,
            'type' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
