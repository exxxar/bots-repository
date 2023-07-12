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
            'article' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'vk_product_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'images' => '{}',
            'type' => $this->faker->numberBetween(-10000, 10000),
            'old_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'current_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'variants' => '{}',
            'in_stop_list_at' => $this->faker->dateTime(),
            'bot_id' => Bot::factory(),
        ];
    }
}
