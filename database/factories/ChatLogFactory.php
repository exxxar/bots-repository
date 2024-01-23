<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\ChatLog;

class ChatLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->text,
            'media_content' => '{}',
            'content_type' => $this->faker->numberBetween(-10000, 10000),
            'bot_id' => Bot::factory(),
            'form_bot_user_id' => BotUser::factory(),
            'to_bot_user_id' => BotUser::factory(),
        ];
    }
}
