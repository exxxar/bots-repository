<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;

class BotPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_menu_slug_id' => BotMenuSlug::factory(),
            'content' => $this->faker->paragraphs(3, true),
            'images' => '{}',
            'reply_keyboard_id' => BotMenuTemplate::factory(),
            'inline_keyboard_id' => BotMenuTemplate::factory(),
            'bot_id' => Bot::factory(),
        ];
    }
}
