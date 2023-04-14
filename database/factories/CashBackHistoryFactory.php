<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\CashBackHistory;
use App\Models\User;

class CashBackHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashBackHistory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'money_in_check' => $this->faker->randomFloat(0, 0, 9999999999.),
            'description' => $this->faker->text,
            'operation_type' => $this->faker->numberBetween(-10000, 10000),
            'user_id' => User::factory(),
            'bot_id' => Bot::factory(),
            'employee_id' => User::factory(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
