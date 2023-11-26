<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BotCustomFieldSetting;
use App\Models\BotUser;
use App\Models\CustomField;

class CustomFieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomField::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'value' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'bot_user_id' => BotUser::factory(),
            'bot_custom_field_setting_id' => BotCustomFieldSetting::factory(),
        ];
    }
}
