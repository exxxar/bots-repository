<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\InlineKeyboard;
use App\Models\InlineQueryItem;
use App\Models\InlineQuerySlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InlineQueryItemController
 */
class InlineQueryItemControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $inlineQueryItems = InlineQueryItem::factory()->count(3)->create();

        $response = $this->get(route('inline-query-item.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InlineQueryItemController::class,
            'store',
            \App\Http\Requests\InlineQueryItemStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $inline_query_slug = InlineQuerySlug::factory()->create();
        $type = $this->faker->numberBetween(-10000, 10000);
        $inline_keyboard = InlineKeyboard::factory()->create();

        $response = $this->post(route('inline-query-item.store'), [
            'inline_query_slug_id' => $inline_query_slug->id,
            'type' => $type,
            'inline_keyboard_id' => $inline_keyboard->id,
        ]);

        $inlineQueryItems = InlineQueryItem::query()
            ->where('inline_query_slug_id', $inline_query_slug->id)
            ->where('type', $type)
            ->where('inline_keyboard_id', $inline_keyboard->id)
            ->get();
        $this->assertCount(1, $inlineQueryItems);
        $inlineQueryItem = $inlineQueryItems->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $inlineQueryItem = InlineQueryItem::factory()->create();

        $response = $this->get(route('inline-query-item.show', $inlineQueryItem));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InlineQueryItemController::class,
            'update',
            \App\Http\Requests\InlineQueryItemUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $inlineQueryItem = InlineQueryItem::factory()->create();
        $inline_query_slug = InlineQuerySlug::factory()->create();
        $type = $this->faker->numberBetween(-10000, 10000);
        $inline_keyboard = InlineKeyboard::factory()->create();

        $response = $this->put(route('inline-query-item.update', $inlineQueryItem), [
            'inline_query_slug_id' => $inline_query_slug->id,
            'type' => $type,
            'inline_keyboard_id' => $inline_keyboard->id,
        ]);

        $inlineQueryItem->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($inline_query_slug->id, $inlineQueryItem->inline_query_slug_id);
        $this->assertEquals($type, $inlineQueryItem->type);
        $this->assertEquals($inline_keyboard->id, $inlineQueryItem->inline_keyboard_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $inlineQueryItem = InlineQueryItem::factory()->create();

        $response = $this->delete(route('inline-query-item.destroy', $inlineQueryItem));

        $response->assertNoContent();

        $this->assertModelMissing($inlineQueryItem);
    }
}
