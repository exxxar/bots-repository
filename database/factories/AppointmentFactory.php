<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use App\Models\Bot;
use App\Models\BotUser;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'bot_user_id' => BotUser::factory(),
            'appointment_schedule_id' => AppointmentSchedule::factory(),
            'status' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
