<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotDialogResult;
use App\Models\BotUser;

class BotDialogResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotDialogResult::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_user_id' => BotUser::factory(),
            'bot_dialog_command_id' => Bot::factory()->create()->dialog_command_id,
            'current_input_data' => '{}',
            'summary_input_data' => '{}',
            'completed_at' => $this->faker->dateTime(),
        ];
    }
}
