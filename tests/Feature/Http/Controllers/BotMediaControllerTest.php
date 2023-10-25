<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotMedia;
use App\Models\BotUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotMediaController
 */
class BotMediaControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botMedia = BotMedia::factory()->count(3)->create();

        $response = $this->get(route('bot-media.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotMediaController::class,
            'store',
            \App\Http\Requests\BotMediaStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();
        $rating = $this->faker->numberBetween(-10000, 10000);
        $type = $this->faker->word;

        $response = $this->post(route('bot-media.store'), [
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
            'rating' => $rating,
            'type' => $type,
        ]);

        $botMedia = BotMedia::query()
            ->where('bot_id', $bot->id)
            ->where('bot_user_id', $bot_user->id)
            ->where('rating', $rating)
            ->where('type', $type)
            ->get();
        $this->assertCount(1, $botMedia);
        $botMedia = $botMedia->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botMedia = BotMedia::factory()->create();

        $response = $this->get(route('bot-media.show', $botMedia));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotMediaController::class,
            'update',
            \App\Http\Requests\BotMediaUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botMedia = BotMedia::factory()->create();
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();
        $rating = $this->faker->numberBetween(-10000, 10000);
        $type = $this->faker->word;

        $response = $this->put(route('bot-media.update', $botMedia), [
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
            'rating' => $rating,
            'type' => $type,
        ]);

        $botMedia->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $botMedia->bot_id);
        $this->assertEquals($bot_user->id, $botMedia->bot_user_id);
        $this->assertEquals($rating, $botMedia->rating);
        $this->assertEquals($type, $botMedia->type);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botMedia = BotMedia::factory()->create();

        $response = $this->delete(route('bot-media.destroy', $botMedia));

        $response->assertNoContent();

        $this->assertModelMissing($botMedia);
    }
}
