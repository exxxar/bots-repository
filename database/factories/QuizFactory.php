<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Quiz;

class QuizFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'description' => $this->faker->text,
            'completed_at' => $this->faker->dateTime(),
            'start_at' => $this->faker->dateTime(),
            'end_at' => $this->faker->dateTime(),
            'display_type' => $this->faker->numberBetween(-10000, 10000),
            'time_limit' => $this->faker->randomFloat(0, 0, 9999999999.),
            'show_answers' => $this->faker->boolean,
            'bot_id' => Bot::factory(),
        ];
    }
}
