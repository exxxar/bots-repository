<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AppointmentEvent;
use App\Models\AppointmentService;

class AppointmentServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppointmentService::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'appointment_event_id' => AppointmentEvent::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'category' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'images' => '{}',
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'discount_price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'need_prepayment' => $this->faker->boolean,
        ];
    }
}
