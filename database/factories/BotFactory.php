<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotType;
use App\Models\Company;

class BotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bot::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'bot_domain' => $this->faker->regexify('[A-Za-z0-9]{190}'),
            'bot_token' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'bot_token_dev' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'order_channel' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'balance' => $this->faker->randomFloat(0, 0, 9999999999.),
            'tax_per_day' => $this->faker->randomFloat(0, 0, 9999999999.),
            'image' => '{}',
            'description' => $this->faker->text,
            'info_link' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'is_active' => $this->faker->boolean,
            'bot_type_id' => BotType::factory(),
            'level_1' => $this->faker->randomFloat(0, 0, 9999999999.),
            'level_2' => $this->faker->randomFloat(0, 0, 9999999999.),
            'level_3' => $this->faker->randomFloat(0, 0, 9999999999.),
            'blocked_message' => $this->faker->text,
            'blocked_at' => $this->faker->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
