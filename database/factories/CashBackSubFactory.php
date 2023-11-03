<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CashBack;
use App\Models\CashBackSub;

class CashBackSubFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashBackSub::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cash_back_id' => CashBack::factory(),
            'title' => $this->faker->sentence(4),
            'amount' => $this->faker->randomFloat(0, 0, 9999999999.),
        ];
    }
}
