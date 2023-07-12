<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Geo;
use App\Models\Order;
use App\Models\User;

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
            'bot_id' => Company::factory(),
            'user_id' => User::factory(),
            'delivery_service_info' => '{}',
            'deliveryman_info' => '{}',
            'product_details' => '{}',
            'product_count' => $this->faker->numberBetween(-10000, 10000),
            'summary_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'delivery_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'delivery_range' => $this->faker->randomFloat(0, 0, 9999999999.),
            'deliveryman_latitude' => $this->faker->randomFloat(0, 0, 9999999999.),
            'deliveryman_longitude' => $this->faker->randomFloat(0, 0, 9999999999.),
            'delivery_note' => $this->faker->text,
            'receiver_name' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'receiver_phone' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'receiver_get_id' => Geo::factory(),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'order_type' => $this->faker->numberBetween(-10000, 10000),
            'payed_at' => $this->faker->dateTime(),
        ];
    }
}
