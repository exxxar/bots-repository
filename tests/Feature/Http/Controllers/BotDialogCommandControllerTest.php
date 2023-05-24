<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotDialogCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotDialogCommandController
 */
class BotDialogCommandControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botDialogCommands = BotDialogCommand::factory()->count(3)->create();

        $response = $this->get(route('bot-dialog-command.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotDialogCommandController::class,
            'store',
            \App\Http\Requests\BotDialogCommandStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $slug = $this->faker->slug;
        $bot = Bot::factory()->create();

        $response = $this->post(route('bot-dialog-command.store'), [
            'slug' => $slug,
            'bot_id' => $bot->id,
        ]);

        $botDialogCommands = BotDialogCommand::query()
            ->where('slug', $slug)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $botDialogCommands);
        $botDialogCommand = $botDialogCommands->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botDialogCommand = BotDialogCommand::factory()->create();

        $response = $this->get(route('bot-dialog-command.show', $botDialogCommand));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotDialogCommandController::class,
            'update',
            \App\Http\Requests\BotDialogCommandUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botDialogCommand = BotDialogCommand::factory()->create();
        $slug = $this->faker->slug;
        $bot = Bot::factory()->create();

        $response = $this->put(route('bot-dialog-command.update', $botDialogCommand), [
            'slug' => $slug,
            'bot_id' => $bot->id,
        ]);

        $botDialogCommand->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($slug, $botDialogCommand->slug);
        $this->assertEquals($bot->id, $botDialogCommand->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botDialogCommand = BotDialogCommand::factory()->create();

        $response = $this->delete(route('bot-dialog-command.destroy', $botDialogCommand));

        $response->assertNoContent();

        $this->assertModelMissing($botDialogCommand);
    }
}
