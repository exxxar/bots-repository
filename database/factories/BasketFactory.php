<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Product;

class BasketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Basket::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'count' => $this->faker->numberBetween(-10000, 10000),
            'bot_user_id' => BotUser::factory(),
            'bot_id' => Bot::factory(),
            'ordered_at' => $this->faker->dateTime(),
        ];
    }
}
