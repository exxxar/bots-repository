<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\ProductCollection;

class ProductCollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCollection::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'owner_id' => BotUser::factory(),
            'title' => $this->faker->sentence(4),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'description' => $this->faker->text(),
            'is_public' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
            'discount' => $this->faker->numberBetween(-10000, 10000),
            'order_position' => $this->faker->numberBetween(-10000, 10000),
            'config' => '{}',
            'bot_user_id' => BotUser::factory(),
        ];
    }
}
