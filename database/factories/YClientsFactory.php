<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\YClients;

class YClientsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = YClients::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'login' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'password' => $this->faker->password,
            'partner_token' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'need_debug' => $this->faker->boolean,
            'debug_log_file' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'throttle' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
