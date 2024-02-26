<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\PromoCode;

class PromoCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoCode::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'code' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'description' => $this->faker->text,
            'slot_amount' => $this->faker->numberBetween(-10000, 10000),
            'cashback_amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'max_activation_count' => $this->faker->numberBetween(-10000, 10000),
            'is_active' => $this->faker->boolean,
        ];
    }
}
