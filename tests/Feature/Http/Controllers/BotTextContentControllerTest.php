<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotTextContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotTextContentController
 */
class BotTextContentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botTextContents = BotTextContent::factory()->count(3)->create();

        $response = $this->get(route('bot-text-content.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotTextContentController::class,
            'store',
            \App\Http\Requests\BotTextContentStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $key = $this->faker->word;
        $bot = Bot::factory()->create();

        $response = $this->post(route('bot-text-content.store'), [
            'key' => $key,
            'bot_id' => $bot->id,
        ]);

        $botTextContents = BotTextContent::query()
            ->where('key', $key)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $botTextContents);
        $botTextContent = $botTextContents->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botTextContent = BotTextContent::factory()->create();

        $response = $this->get(route('bot-text-content.show', $botTextContent));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotTextContentController::class,
            'update',
            \App\Http\Requests\BotTextContentUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botTextContent = BotTextContent::factory()->create();
        $key = $this->faker->word;
        $bot = Bot::factory()->create();

        $response = $this->put(route('bot-text-content.update', $botTextContent), [
            'key' => $key,
            'bot_id' => $bot->id,
        ]);

        $botTextContent->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($key, $botTextContent->key);
        $this->assertEquals($bot->id, $botTextContent->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botTextContent = BotTextContent::factory()->create();

        $response = $this->delete(route('bot-text-content.destroy', $botTextContent));

        $response->assertNoContent();

        $this->assertModelMissing($botTextContent);
    }
}
