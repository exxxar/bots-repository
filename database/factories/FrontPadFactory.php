<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\FrontPad;

class FrontPadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FrontPad::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'hook_url' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'channel' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'affiliate' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'point' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'token' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
