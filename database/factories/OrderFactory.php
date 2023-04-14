<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->numberBetween(-10000, 10000),
            'need_delivery' => $this->faker->boolean,
            'delivery_address' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'comment' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'summary_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'payed_at' => $this->faker->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
