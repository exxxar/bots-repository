<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotTextContent;

class BotTextContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotTextContent::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'value' => $this->faker->text,
            'key' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'bot_id' => Bot::factory(),
        ];
    }
}
