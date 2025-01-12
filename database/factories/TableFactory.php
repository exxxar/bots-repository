<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Table;

class TableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Table::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'creator_id' => BotUser::factory(),
            'officiant_id' => BotUser::factory(),
            'number' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'closed_at' => $this->faker->dateTime(),
            'config' => '{}',
            'bot_user_id' => BotUser::factory(),
        ];
    }
}
