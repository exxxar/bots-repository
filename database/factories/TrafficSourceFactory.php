<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\TrafficSource;

class TrafficSourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrafficSource::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'bot_user_id' => BotUser::factory(),
            'comment' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'source' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
