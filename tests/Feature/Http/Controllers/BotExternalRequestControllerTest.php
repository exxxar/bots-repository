<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotExternalRequest;
use App\Models\BotUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotExternalRequestController
 */
class BotExternalRequestControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botExternalRequests = BotExternalRequest::factory()->count(3)->create();

        $response = $this->get(route('bot-external-request.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotExternalRequestController::class,
            'store',
            \App\Http\Requests\BotExternalRequestStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();

        $response = $this->post(route('bot-external-request.store'), [
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
        ]);

        $botExternalRequests = BotExternalRequest::query()
            ->where('bot_id', $bot->id)
            ->where('bot_user_id', $bot_user->id)
            ->get();
        $this->assertCount(1, $botExternalRequests);
        $botExternalRequest = $botExternalRequests->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botExternalRequest = BotExternalRequest::factory()->create();

        $response = $this->get(route('bot-external-request.show', $botExternalRequest));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotExternalRequestController::class,
            'update',
            \App\Http\Requests\BotExternalRequestUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botExternalRequest = BotExternalRequest::factory()->create();
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();

        $response = $this->put(route('bot-external-request.update', $botExternalRequest), [
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
        ]);

        $botExternalRequest->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $botExternalRequest->bot_id);
        $this->assertEquals($bot_user->id, $botExternalRequest->bot_user_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botExternalRequest = BotExternalRequest::factory()->create();

        $response = $this->delete(route('bot-external-request.destroy', $botExternalRequest));

        $response->assertNoContent();

        $this->assertModelMissing($botExternalRequest);
    }
}
