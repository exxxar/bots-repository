<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\QuizQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\QuizQuestionController
 */
class QuizQuestionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $quizQuestions = QuizQuestion::factory()->count(3)->create();

        $response = $this->get(route('quiz-question.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizQuestionController::class,
            'store',
            \App\Http\Requests\QuizQuestionStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $content_type = $this->faker->numberBetween(-10000, 10000);
        $is_multiply = $this->faker->boolean;
        $is_open = $this->faker->boolean;
        $round = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('quiz-question.store'), [
            'content_type' => $content_type,
            'is_multiply' => $is_multiply,
            'is_open' => $is_open,
            'round' => $round,
        ]);

        $quizQuestions = QuizQuestion::query()
            ->where('content_type', $content_type)
            ->where('is_multiply', $is_multiply)
            ->where('is_open', $is_open)
            ->where('round', $round)
            ->get();
        $this->assertCount(1, $quizQuestions);
        $quizQuestion = $quizQuestions->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $quizQuestion = QuizQuestion::factory()->create();

        $response = $this->get(route('quiz-question.show', $quizQuestion));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizQuestionController::class,
            'update',
            \App\Http\Requests\QuizQuestionUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $quizQuestion = QuizQuestion::factory()->create();
        $content_type = $this->faker->numberBetween(-10000, 10000);
        $is_multiply = $this->faker->boolean;
        $is_open = $this->faker->boolean;
        $round = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('quiz-question.update', $quizQuestion), [
            'content_type' => $content_type,
            'is_multiply' => $is_multiply,
            'is_open' => $is_open,
            'round' => $round,
        ]);

        $quizQuestion->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($content_type, $quizQuestion->content_type);
        $this->assertEquals($is_multiply, $quizQuestion->is_multiply);
        $this->assertEquals($is_open, $quizQuestion->is_open);
        $this->assertEquals($round, $quizQuestion->round);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $quizQuestion = QuizQuestion::factory()->create();

        $response = $this->delete(route('quiz-question.destroy', $quizQuestion));

        $response->assertNoContent();

        $this->assertModelMissing($quizQuestion);
    }
}
