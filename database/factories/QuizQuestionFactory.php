<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\QuizQuestion;

class QuizQuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizQuestion::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->text,
            'media_content' => '{}',
            'content_type' => $this->faker->numberBetween(-10000, 10000),
            'is_multiply' => $this->faker->boolean,
            'is_open' => $this->faker->boolean,
            'round' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
