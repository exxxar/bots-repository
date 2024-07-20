<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'order_id' => Order::factory(),
            'bot_user_id' => Order::factory(),
            'product_id' => Product::factory(),
            'text' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'rating' => $this->faker->randomFloat(0, 0, 9999999999.),
            'send_review_at' => $this->faker->dateTime(),
        ];
    }
}
