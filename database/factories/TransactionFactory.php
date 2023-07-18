<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Transaction;
use App\Models\User;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bot_id' => Bot::factory(),
            'payload' => $this->faker->regexify('[A-Za-z0-9]{128}'),
            'currency' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            'total_amount' => $this->faker->numberBetween(-10000, 10000),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'order_info' => '{}',
            'products_info' => '{}',
            'shipping_address' => '{}',
            'telegram_payment_charge_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'provider_payment_charge_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'completed_at' => $this->faker->dateTime(),
        ];
    }
}
