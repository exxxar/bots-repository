<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Quiz;
use App\Models\QuizCommand;
use App\Models\QuizResult;

class QuizResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizResult::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'quiz_command_id' => QuizCommand::factory(),
            'points' => $this->faker->randomFloat(0, 0, 9999999999.),
            'time' => $this->faker->randomFloat(0, 0, 9999999999.),
            'result' => '{}',
        ];
    }
}
