<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

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
            'user_id' => User::factory(),
            'bot_id' => Bot::factory(),
            'order_id' => Order::factory(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
