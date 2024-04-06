<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BotDialogAnswer;
use App\Models\BotDialogCommand;

class BotDialogAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotDialogAnswer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_dialog_command_id' => BotDialogCommand::factory(),
            'answer' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'pattern' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'next_bot_dialog_command_id' => BotDialogCommand::factory(),
        ];
    }
}
