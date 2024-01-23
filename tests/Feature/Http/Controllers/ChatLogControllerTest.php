<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\ChatLog;
use App\Models\FormBotUser;
use App\Models\ToBotUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ChatLogController
 */
class ChatLogControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $chatLogs = ChatLog::factory()->count(3)->create();

        $response = $this->get(route('chat-log.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ChatLogController::class,
            'store',
            \App\Http\Requests\ChatLogStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $content_type = $this->faker->numberBetween(-10000, 10000);
        $bot = Bot::factory()->create();
        $form_bot_user = FormBotUser::factory()->create();
        $to_bot_user = ToBotUser::factory()->create();

        $response = $this->post(route('chat-log.store'), [
            'content_type' => $content_type,
            'bot_id' => $bot->id,
            'form_bot_user_id' => $form_bot_user->id,
            'to_bot_user_id' => $to_bot_user->id,
        ]);

        $chatLogs = ChatLog::query()
            ->where('content_type', $content_type)
            ->where('bot_id', $bot->id)
            ->where('form_bot_user_id', $form_bot_user->id)
            ->where('to_bot_user_id', $to_bot_user->id)
            ->get();
        $this->assertCount(1, $chatLogs);
        $chatLog = $chatLogs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $chatLog = ChatLog::factory()->create();

        $response = $this->get(route('chat-log.show', $chatLog));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ChatLogController::class,
            'update',
            \App\Http\Requests\ChatLogUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $chatLog = ChatLog::factory()->create();
        $content_type = $this->faker->numberBetween(-10000, 10000);
        $bot = Bot::factory()->create();
        $form_bot_user = FormBotUser::factory()->create();
        $to_bot_user = ToBotUser::factory()->create();

        $response = $this->put(route('chat-log.update', $chatLog), [
            'content_type' => $content_type,
            'bot_id' => $bot->id,
            'form_bot_user_id' => $form_bot_user->id,
            'to_bot_user_id' => $to_bot_user->id,
        ]);

        $chatLog->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($content_type, $chatLog->content_type);
        $this->assertEquals($bot->id, $chatLog->bot_id);
        $this->assertEquals($form_bot_user->id, $chatLog->form_bot_user_id);
        $this->assertEquals($to_bot_user->id, $chatLog->to_bot_user_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $chatLog = ChatLog::factory()->create();

        $response = $this->delete(route('chat-log.destroy', $chatLog));

        $response->assertNoContent();

        $this->assertModelMissing($chatLog);
    }
}
