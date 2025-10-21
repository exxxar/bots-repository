<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Partner;

class PartnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Partner::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'bot_partner_id' => Bot::factory()->create()->partner_id,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text(),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'is_active' => $this->faker->boolean(),
            'extra_charge' => $this->faker->numberBetween(-10000, 10000),
            'config' => '{}',
            'legal_info' => '{}',
        ];
    }
}
