<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotWarning;

class BotWarningFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotWarning::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'rule_key' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'rule_value' => $this->faker->numberBetween(-10000, 10000),
            'is_active' => $this->faker->boolean,
        ];
    }
}
