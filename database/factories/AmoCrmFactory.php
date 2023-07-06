<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AmoCrm;
use App\Models\Bot;

class AmoCrmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AmoCrm::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'client_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'client_secret' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'auth_code' => $this->faker->regexify('[A-Za-z0-9]{1000}'),
            'redirect_uri' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'subdomain' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
