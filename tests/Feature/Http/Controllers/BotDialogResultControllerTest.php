<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BotDialogResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotDialogResultController
 */
class BotDialogResultControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botDialogResults = BotDialogResult::factory()->count(3)->create();

        $response = $this->get(route('bot-dialog-result.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotDialogResultController::class,
            'store',
            \App\Http\Requests\BotDialogResultStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $response = $this->post(route('bot-dialog-result.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(botDialogResults, [ /* ... */ ]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botDialogResult = BotDialogResult::factory()->create();

        $response = $this->get(route('bot-dialog-result.show', $botDialogResult));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotDialogResultController::class,
            'update',
            \App\Http\Requests\BotDialogResultUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botDialogResult = BotDialogResult::factory()->create();

        $response = $this->put(route('bot-dialog-result.update', $botDialogResult));

        $botDialogResult->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botDialogResult = BotDialogResult::factory()->create();

        $response = $this->delete(route('bot-dialog-result.destroy', $botDialogResult));

        $response->assertNoContent();

        $this->assertModelMissing($botDialogResult);
    }
}
