<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BotUser;
use App\Models\ManagerProfile;

class ManagerProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ManagerProfile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_user_id' => BotUser::factory(),
            'info' => $this->faker->text,
            'referral' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'strengths' => '{}',
            'weaknesses' => '{}',
            'educations' => '{}',
            'social_links' => '{}',
            'skills' => '{}',
            'stable_personal_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'permanent_personal_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'max_company_slot_count' => $this->faker->numberBetween(-10000, 10000),
            'max_bot_slot_count' => $this->faker->numberBetween(-10000, 10000),
            'balance' => $this->faker->numberBetween(-10000, 10000),
            'verified_at' => $this->faker->dateTime(),
        ];
    }
}
