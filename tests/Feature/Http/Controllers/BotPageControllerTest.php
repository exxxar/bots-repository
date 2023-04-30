<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotPageController
 */
class BotPageControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botPages = BotPage::factory()->count(3)->create();

        $response = $this->get(route('bot-page.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotPageController::class,
            'store',
            \App\Http\Requests\BotPageStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot_menu_slug = BotMenuSlug::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->post(route('bot-page.store'), [
            'bot_menu_slug_id' => $bot_menu_slug->id,
            'bot_id' => $bot->id,
        ]);

        $botPages = BotPage::query()
            ->where('bot_menu_slug_id', $bot_menu_slug->id)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $botPages);
        $botPage = $botPages->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botPage = BotPage::factory()->create();

        $response = $this->get(route('bot-page.show', $botPage));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotPageController::class,
            'update',
            \App\Http\Requests\BotPageUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botPage = BotPage::factory()->create();
        $bot_menu_slug = BotMenuSlug::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('bot-page.update', $botPage), [
            'bot_menu_slug_id' => $bot_menu_slug->id,
            'bot_id' => $bot->id,
        ]);

        $botPage->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot_menu_slug->id, $botPage->bot_menu_slug_id);
        $this->assertEquals($bot->id, $botPage->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botPage = BotPage::factory()->create();

        $response = $this->delete(route('bot-page.destroy', $botPage));

        $response->assertNoContent();

        $this->assertModelMissing($botPage);
    }
}
