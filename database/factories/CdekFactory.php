<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Cdek;

class CdekFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cdek::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'account' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'secure_password' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'is_active' => $this->faker->boolean(),
            'config' => '{}',
        ];
    }
}
