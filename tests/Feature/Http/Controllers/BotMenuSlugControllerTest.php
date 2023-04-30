<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotMenuSlugController
 */
class BotMenuSlugControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botMenuSlugs = BotMenuSlug::factory()->count(3)->create();

        $response = $this->get(route('bot-menu-slug.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotMenuSlugController::class,
            'store',
            \App\Http\Requests\BotMenuSlugStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();

        $response = $this->post(route('bot-menu-slug.store'), [
            'bot_id' => $bot->id,
        ]);

        $botMenuSlugs = BotMenuSlug::query()
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $botMenuSlugs);
        $botMenuSlug = $botMenuSlugs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botMenuSlug = BotMenuSlug::factory()->create();

        $response = $this->get(route('bot-menu-slug.show', $botMenuSlug));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotMenuSlugController::class,
            'update',
            \App\Http\Requests\BotMenuSlugUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botMenuSlug = BotMenuSlug::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('bot-menu-slug.update', $botMenuSlug), [
            'bot_id' => $bot->id,
        ]);

        $botMenuSlug->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $botMenuSlug->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botMenuSlug = BotMenuSlug::factory()->create();

        $response = $this->delete(route('bot-menu-slug.destroy', $botMenuSlug));

        $response->assertNoContent();

        $this->assertModelMissing($botMenuSlug);
    }
}
