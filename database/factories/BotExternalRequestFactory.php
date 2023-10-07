<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotExternalRequest;
use App\Models\BotUser;

class BotExternalRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotExternalRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'bot_user_id' => BotUser::factory(),
            'command' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'completed_at' => $this->faker->dateTime(),
        ];
    }
}
