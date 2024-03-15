<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\InlineQuerySlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\InlineQuerySlugController
 */
class InlineQuerySlugControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $inlineQuerySlugs = InlineQuerySlug::factory()->count(3)->create();

        $response = $this->get(route('inline-query-slug.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\InlineQuerySlugController::class,
            'store',
            \App\Http\Requests\InlineQuerySlugStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();

        $response = $this->post(route('inline-query-slug.store'), [
            'bot_id' => $bot->id,
        ]);

        $inlineQuerySlugs = InlineQuerySlug::query()
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $inlineQuerySlugs);
        $inlineQuerySlug = $inlineQuerySlugs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $inlineQuerySlug = InlineQuerySlug::factory()->create();

        $response = $this->get(route('inline-query-slug.show', $inlineQuerySlug));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\InlineQuerySlugController::class,
            'update',
            \App\Http\Requests\InlineQuerySlugUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $inlineQuerySlug = InlineQuerySlug::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('inline-query-slug.update', $inlineQuerySlug), [
            'bot_id' => $bot->id,
        ]);

        $inlineQuerySlug->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $inlineQuerySlug->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $inlineQuerySlug = InlineQuerySlug::factory()->create();

        $response = $this->delete(route('inline-query-slug.destroy', $inlineQuerySlug));

        $response->assertNoContent();

        $this->assertModelMissing($inlineQuerySlug);
    }
}
