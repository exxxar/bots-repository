<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotDialogCommand;
use App\Models\BotMenuTemplate;

class BotDialogCommandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotDialogCommand::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'pre_text' => $this->faker->text,
            'post_text' => $this->faker->text,
            'error_text' => $this->faker->text,
            'bot_id' => Bot::factory(),
            'input_pattern' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'inline_keyboard_id' => null,
            'images' => '{}',
            'next_bot_dialog_command_id' =>  null,
            'result_channel' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
