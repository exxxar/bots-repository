<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotProduct;
use App\Models\User;

class BotProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BotProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'slug' => $this->faker->slug,
            'description' => $this->faker->text,
            'images' => '{}',
            'base_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'discount_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'weight' => $this->faker->randomFloat(0, 0, 9999999999.),
            'count' => $this->faker->word,
            'in_stock' => $this->faker->boolean,
            'specifications' => '{}',
            'variants' => '{}',
            'owner_id' =>1,
            'bot_id' => 1,
        ];
    }
}
