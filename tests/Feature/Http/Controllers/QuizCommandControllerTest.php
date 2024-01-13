<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\QuizCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\QuizCommandController
 */
class QuizCommandControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $quizCommands = QuizCommand::factory()->count(3)->create();

        $response = $this->get(route('quiz-command.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizCommandController::class,
            'store',
            \App\Http\Requests\QuizCommandStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $response = $this->post(route('quiz-command.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(quizCommands, [ /* ... */ ]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $quizCommand = QuizCommand::factory()->create();

        $response = $this->get(route('quiz-command.show', $quizCommand));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\QuizCommandController::class,
            'update',
            \App\Http\Requests\QuizCommandUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $quizCommand = QuizCommand::factory()->create();

        $response = $this->put(route('quiz-command.update', $quizCommand));

        $quizCommand->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $quizCommand = QuizCommand::factory()->create();

        $response = $this->delete(route('quiz-command.destroy', $quizCommand));

        $response->assertNoContent();

        $this->assertModelMissing($quizCommand);
    }
}
