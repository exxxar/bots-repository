<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\ReferralHistory;
use App\Models\User;

class ReferralHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReferralHistory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_sender_id' => User::factory()->create()->sender_id,
            'user_recipient_id' => User::factory()->create()->recipient_id,
            'bot_id' => Bot::factory(),
            'activated' => $this->faker->boolean,
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
