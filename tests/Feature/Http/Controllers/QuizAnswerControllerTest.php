<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\QuizAnswerController
 */
class QuizAnswerControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $quizAnswers = QuizAnswer::factory()->count(3)->create();

        $response = $this->get(route('quiz-answer.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizAnswerController::class,
            'store',
            \App\Http\Requests\QuizAnswerStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $quiz_question = QuizQuestion::factory()->create();
        $content_type = $this->faker->numberBetween(-10000, 10000);
        $is_right_answer = $this->faker->boolean;
        $points = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->post(route('quiz-answer.store'), [
            'quiz_question_id' => $quiz_question->id,
            'content_type' => $content_type,
            'is_right_answer' => $is_right_answer,
            'points' => $points,
        ]);

        $quizAnswers = QuizAnswer::query()
            ->where('quiz_question_id', $quiz_question->id)
            ->where('content_type', $content_type)
            ->where('is_right_answer', $is_right_answer)
            ->where('points', $points)
            ->get();
        $this->assertCount(1, $quizAnswers);
        $quizAnswer = $quizAnswers->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $quizAnswer = QuizAnswer::factory()->create();

        $response = $this->get(route('quiz-answer.show', $quizAnswer));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizAnswerController::class,
            'update',
            \App\Http\Requests\QuizAnswerUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $quizAnswer = QuizAnswer::factory()->create();
        $quiz_question = QuizQuestion::factory()->create();
        $content_type = $this->faker->numberBetween(-10000, 10000);
        $is_right_answer = $this->faker->boolean;
        $points = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->put(route('quiz-answer.update', $quizAnswer), [
            'quiz_question_id' => $quiz_question->id,
            'content_type' => $content_type,
            'is_right_answer' => $is_right_answer,
            'points' => $points,
        ]);

        $quizAnswer->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($quiz_question->id, $quizAnswer->quiz_question_id);
        $this->assertEquals($content_type, $quizAnswer->content_type);
        $this->assertEquals($is_right_answer, $quizAnswer->is_right_answer);
        $this->assertEquals($points, $quizAnswer->points);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $quizAnswer = QuizAnswer::factory()->create();

        $response = $this->delete(route('quiz-answer.destroy', $quizAnswer));

        $response->assertNoContent();

        $this->assertModelMissing($quizAnswer);
    }
}
