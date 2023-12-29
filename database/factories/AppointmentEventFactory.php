<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AppointmentEvent;
use App\Models\Bot;

class AppointmentEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppointmentEvent::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'bot_id' => Bot::factory(),
            'title' => $this->faker->sentence(4),
            'subtitle' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'description' => $this->faker->text,
            'images' => '{}',
            'is_group' => $this->faker->boolean,
            'max_people' => $this->faker->numberBetween(-10000, 10000),
            'mix_people' => $this->faker->numberBetween(-10000, 10000),
            'on_start_appointment' => $this->faker->text,
            'on_cancel_appointment' => $this->faker->text,
            'on_after_appointment' => $this->faker->text,
            'on_repeat_appointment' => $this->faker->text,
        ];
    }
}
