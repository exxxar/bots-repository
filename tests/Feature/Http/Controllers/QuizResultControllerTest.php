<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizCommand;
use App\Models\QuizResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\QuizResultController
 */
class QuizResultControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $quizResults = QuizResult::factory()->count(3)->create();

        $response = $this->get(route('quiz-result.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizResultController::class,
            'store',
            \App\Http\Requests\QuizResultStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $quiz = Quiz::factory()->create();
        $quiz_command = QuizCommand::factory()->create();
        $points = $this->faker->randomFloat(/** double_attributes **/);
        $time = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->post(route('quiz-result.store'), [
            'quiz_id' => $quiz->id,
            'quiz_command_id' => $quiz_command->id,
            'points' => $points,
            'time' => $time,
        ]);

        $quizResults = QuizResult::query()
            ->where('quiz_id', $quiz->id)
            ->where('quiz_command_id', $quiz_command->id)
            ->where('points', $points)
            ->where('time', $time)
            ->get();
        $this->assertCount(1, $quizResults);
        $quizResult = $quizResults->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $quizResult = QuizResult::factory()->create();

        $response = $this->get(route('quiz-result.show', $quizResult));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizResultController::class,
            'update',
            \App\Http\Requests\QuizResultUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $quizResult = QuizResult::factory()->create();
        $quiz = Quiz::factory()->create();
        $quiz_command = QuizCommand::factory()->create();
        $points = $this->faker->randomFloat(/** double_attributes **/);
        $time = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->put(route('quiz-result.update', $quizResult), [
            'quiz_id' => $quiz->id,
            'quiz_command_id' => $quiz_command->id,
            'points' => $points,
            'time' => $time,
        ]);

        $quizResult->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($quiz->id, $quizResult->quiz_id);
        $this->assertEquals($quiz_command->id, $quizResult->quiz_command_id);
        $this->assertEquals($points, $quizResult->points);
        $this->assertEquals($time, $quizResult->time);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $quizResult = QuizResult::factory()->create();

        $response = $this->delete(route('quiz-result.destroy', $quizResult));

        $response->assertNoContent();

        $this->assertModelMissing($quizResult);
    }
}
