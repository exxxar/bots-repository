<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\QuizController
 */
class QuizControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $quizzes = Quiz::factory()->count(3)->create();

        $response = $this->get(route('quiz.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\QuizController::class,
            'store',
            \App\Http\Requests\QuizStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $display_type = $this->faker->numberBetween(-10000, 10000);
        $time_limit = $this->faker->randomFloat(/** double_attributes **/);
        $show_answers = $this->faker->boolean;
        $bot = Bot::factory()->create();

        $response = $this->post(route('quiz.store'), [
            'display_type' => $display_type,
            'time_limit' => $time_limit,
            'show_answers' => $show_answers,
            'bot_id' => $bot->id,
        ]);

        $quizzes = Quiz::query()
            ->where('display_type', $display_type)
            ->where('time_limit', $time_limit)
            ->where('show_answers', $show_answers)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $quizzes);
        $quiz = $quizzes->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $quiz = Quiz::factory()->create();

        $response = $this->get(route('quiz.show', $quiz));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\QuizController::class,
            'update',
            \App\Http\Requests\QuizUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $quiz = Quiz::factory()->create();
        $display_type = $this->faker->numberBetween(-10000, 10000);
        $time_limit = $this->faker->randomFloat(/** double_attributes **/);
        $show_answers = $this->faker->boolean;
        $bot = Bot::factory()->create();

        $response = $this->put(route('quiz.update', $quiz), [
            'display_type' => $display_type,
            'time_limit' => $time_limit,
            'show_answers' => $show_answers,
            'bot_id' => $bot->id,
        ]);

        $quiz->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($display_type, $quiz->display_type);
        $this->assertEquals($time_limit, $quiz->time_limit);
        $this->assertEquals($show_answers, $quiz->show_answers);
        $this->assertEquals($bot->id, $quiz->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $quiz = Quiz::factory()->create();

        $response = $this->delete(route('quiz.destroy', $quiz));

        $response->assertNoContent();

        $this->assertModelMissing($quiz);
    }
}
