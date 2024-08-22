<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Iiko;

class IikoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Iiko::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'api_login' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'organization_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'terminal_group_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
