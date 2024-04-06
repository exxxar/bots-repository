<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BotDialogAnswer;
use App\Models\BotDialogCommand;
use App\Models\NextBotDialogCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotDialogAnswerController
 */
class BotDialogAnswerControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botDialogAnswers = BotDialogAnswer::factory()->count(3)->create();

        $response = $this->get(route('bot-dialog-answer.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotDialogAnswerController::class,
            'store',
            \App\Http\Requests\BotDialogAnswerStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot_dialog_command = BotDialogCommand::factory()->create();
        $next_bot_dialog_command = NextBotDialogCommand::factory()->create();

        $response = $this->post(route('bot-dialog-answer.store'), [
            'bot_dialog_command_id' => $bot_dialog_command->id,
            'next_bot_dialog_command_id' => $next_bot_dialog_command->id,
        ]);

        $botDialogAnswers = BotDialogAnswer::query()
            ->where('bot_dialog_command_id', $bot_dialog_command->id)
            ->where('next_bot_dialog_command_id', $next_bot_dialog_command->id)
            ->get();
        $this->assertCount(1, $botDialogAnswers);
        $botDialogAnswer = $botDialogAnswers->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botDialogAnswer = BotDialogAnswer::factory()->create();

        $response = $this->get(route('bot-dialog-answer.show', $botDialogAnswer));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotDialogAnswerController::class,
            'update',
            \App\Http\Requests\BotDialogAnswerUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botDialogAnswer = BotDialogAnswer::factory()->create();
        $bot_dialog_command = BotDialogCommand::factory()->create();
        $next_bot_dialog_command = NextBotDialogCommand::factory()->create();

        $response = $this->put(route('bot-dialog-answer.update', $botDialogAnswer), [
            'bot_dialog_command_id' => $bot_dialog_command->id,
            'next_bot_dialog_command_id' => $next_bot_dialog_command->id,
        ]);

        $botDialogAnswer->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot_dialog_command->id, $botDialogAnswer->bot_dialog_command_id);
        $this->assertEquals($next_bot_dialog_command->id, $botDialogAnswer->next_bot_dialog_command_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botDialogAnswer = BotDialogAnswer::factory()->create();

        $response = $this->delete(route('bot-dialog-answer.destroy', $botDialogAnswer));

        $response->assertNoContent();

        $this->assertModelMissing($botDialogAnswer);
    }
}
