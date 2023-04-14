<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\NotificationSchedule;
use App\Models\User;

class NotificationScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NotificationSchedule::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'message' => $this->faker->text,
            'info_link' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'start_at' => $this->faker->dateTime(),
            'bot_id' => Bot::factory(),
            'creator_id' => User::factory(),
            'completed_at' => $this->faker->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
