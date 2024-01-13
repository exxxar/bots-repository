<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;

class QuizAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizAnswer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quiz_question_id' => QuizQuestion::factory(),
            'text' => $this->faker->text,
            'media_content' => '{}',
            'content_type' => $this->faker->numberBetween(-10000, 10000),
            'is_right_answer' => $this->faker->boolean,
            'points' => $this->faker->randomFloat(0, 0, 9999999999.),
        ];
    }
}
