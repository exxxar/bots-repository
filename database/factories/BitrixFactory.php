<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bitrix;
use App\Models\Bot;

class BitrixFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bitrix::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'host' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'client_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'client_secret' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'scopes' => '{}',
        ];
    }
}
