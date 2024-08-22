<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Shop;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'title' => $this->faker->sentence(4),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'key' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'is_default' => $this->faker->boolean(),
            'config' => '{}',
        ];
    }
}
