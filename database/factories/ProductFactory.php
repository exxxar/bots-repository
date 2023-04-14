<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'weight' => $this->faker->randomFloat(0, 0, 9999999999.),
            'base_price_before_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'base_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'portion_count' => $this->faker->numberBetween(-10000, 10000),
            'is_active' => $this->faker->boolean,
            'images' => '{}',
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
