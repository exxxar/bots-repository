<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\InlineQuerySlug;

class InlineQuerySlugFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InlineQuerySlug::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'command' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'description' => $this->faker->text,
        ];
    }
}