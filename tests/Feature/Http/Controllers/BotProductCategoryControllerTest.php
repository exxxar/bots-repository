<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotProductCategoryController
 */
class BotProductCategoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botProductCategories = BotProductCategory::factory()->count(3)->create();

        $response = $this->get(route('bot-product-category.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotProductCategoryController::class,
            'store',
            \App\Http\Requests\BotProductCategoryStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $slug = $this->faker->slug;
        $bot = Bot::factory()->create();

        $response = $this->post(route('bot-product-category.store'), [
            'slug' => $slug,
            'bot_id' => $bot->id,
        ]);

        $botProductCategories = BotProductCategory::query()
            ->where('slug', $slug)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $botProductCategories);
        $botProductCategory = $botProductCategories->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botProductCategory = BotProductCategory::factory()->create();

        $response = $this->get(route('bot-product-category.show', $botProductCategory));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotProductCategoryController::class,
            'update',
            \App\Http\Requests\BotProductCategoryUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botProductCategory = BotProductCategory::factory()->create();
        $slug = $this->faker->slug;
        $bot = Bot::factory()->create();

        $response = $this->put(route('bot-product-category.update', $botProductCategory), [
            'slug' => $slug,
            'bot_id' => $bot->id,
        ]);

        $botProductCategory->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($slug, $botProductCategory->slug);
        $this->assertEquals($bot->id, $botProductCategory->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botProductCategory = BotProductCategory::factory()->create();

        $response = $this->delete(route('bot-product-category.destroy', $botProductCategory));

        $response->assertNoContent();

        $this->assertModelMissing($botProductCategory);
    }
}
