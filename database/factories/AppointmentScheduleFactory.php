<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AppointmentEvent;
use App\Models\AppointmentSchedule;

class AppointmentScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppointmentSchedule::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'appointment_event_id' => AppointmentEvent::factory(),
            'start_time' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            'end_time' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            'day' => $this->faker->word,
        ];
    }
}
