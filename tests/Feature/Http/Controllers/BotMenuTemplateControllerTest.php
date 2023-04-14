<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotMenuTemplateController
 */
class BotMenuTemplateControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botMenuTemplates = BotMenuTemplate::factory()->count(3)->create();

        $response = $this->get(route('bot-menu-template.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotMenuTemplateController::class,
            'store',
            \App\Http\Requests\BotMenuTemplateStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $type = $this->faker->numberBetween(-10000, 10000);
        $slug = $this->faker->slug;

        $response = $this->post(route('bot-menu-template.store'), [
            'bot_id' => $bot->id,
            'type' => $type,
            'slug' => $slug,
        ]);

        $botMenuTemplates = BotMenuTemplate::query()
            ->where('bot_id', $bot->id)
            ->where('type', $type)
            ->where('slug', $slug)
            ->get();
        $this->assertCount(1, $botMenuTemplates);
        $botMenuTemplate = $botMenuTemplates->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botMenuTemplate = BotMenuTemplate::factory()->create();

        $response = $this->get(route('bot-menu-template.show', $botMenuTemplate));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotMenuTemplateController::class,
            'update',
            \App\Http\Requests\BotMenuTemplateUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botMenuTemplate = BotMenuTemplate::factory()->create();
        $bot = Bot::factory()->create();
        $type = $this->faker->numberBetween(-10000, 10000);
        $slug = $this->faker->slug;

        $response = $this->put(route('bot-menu-template.update', $botMenuTemplate), [
            'bot_id' => $bot->id,
            'type' => $type,
            'slug' => $slug,
        ]);

        $botMenuTemplate->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $botMenuTemplate->bot_id);
        $this->assertEquals($type, $botMenuTemplate->type);
        $this->assertEquals($slug, $botMenuTemplate->slug);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botMenuTemplate = BotMenuTemplate::factory()->create();

        $response = $this->delete(route('bot-menu-template.destroy', $botMenuTemplate));

        $response->assertNoContent();

        $this->assertModelMissing($botMenuTemplate);
    }
}
