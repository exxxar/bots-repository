<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\SubShop;

class SubShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubShop::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'title' => $this->faker->sentence(4),
            'keyword' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'schedule' => '{}',
            'config' => '{}',
            'is_active' => $this->faker->boolean(),
        ];
    }
}
