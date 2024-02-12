<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BotMenuTemplate;
use App\Models\InlineQueryItem;
use App\Models\InlineQuerySlug;

class InlineQueryItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InlineQueryItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'inline_query_slug_id' => InlineQuerySlug::factory(),
            'type' => $this->faker->numberBetween(-10000, 10000),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'input_message_content' => '{}',
            'inline_keyboard_id' => BotMenuTemplate::factory(),
            'custom_settings' => '{}',
        ];
    }
}
