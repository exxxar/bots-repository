<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AppointmentEvent;
use App\Models\AppointmentReview;

class AppointmentReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppointmentReview::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'appointment_event_id' => AppointmentEvent::factory(),
            'rating' => $this->faker->numberBetween(-10000, 10000),
            'images' => '{}',
            'text' => $this->faker->text,
        ];
    }
}
