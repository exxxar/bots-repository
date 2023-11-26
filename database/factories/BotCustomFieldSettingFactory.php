<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotCustomFieldSetting;

class BotCustomFieldSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotCustomFieldSetting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'type' => $this->faker->numberBetween(-10000, 10000),
            'label' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'description' => $this->faker->text,
            'required' => $this->faker->boolean,
            'validate_pattern' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
