<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotDialogGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotDialogGroupController
 */
class BotDialogGroupControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botDialogGroups = BotDialogGroup::factory()->count(3)->create();

        $response = $this->get(route('bot-dialog-group.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotDialogGroupController::class,
            'store',
            \App\Http\Requests\BotDialogGroupStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $slug = $this->faker->slug;
        $title = $this->faker->sentence(4);
        $bot = Bot::factory()->create();

        $response = $this->post(route('bot-dialog-group.store'), [
            'slug' => $slug,
            'title' => $title,
            'bot_id' => $bot->id,
        ]);

        $botDialogGroups = BotDialogGroup::query()
            ->where('slug', $slug)
            ->where('title', $title)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $botDialogGroups);
        $botDialogGroup = $botDialogGroups->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botDialogGroup = BotDialogGroup::factory()->create();

        $response = $this->get(route('bot-dialog-group.show', $botDialogGroup));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotDialogGroupController::class,
            'update',
            \App\Http\Requests\BotDialogGroupUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botDialogGroup = BotDialogGroup::factory()->create();
        $slug = $this->faker->slug;
        $title = $this->faker->sentence(4);
        $bot = Bot::factory()->create();

        $response = $this->put(route('bot-dialog-group.update', $botDialogGroup), [
            'slug' => $slug,
            'title' => $title,
            'bot_id' => $bot->id,
        ]);

        $botDialogGroup->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($slug, $botDialogGroup->slug);
        $this->assertEquals($title, $botDialogGroup->title);
        $this->assertEquals($bot->id, $botDialogGroup->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botDialogGroup = BotDialogGroup::factory()->create();

        $response = $this->delete(route('bot-dialog-group.destroy', $botDialogGroup));

        $response->assertNoContent();

        $this->assertModelMissing($botDialogGroup);
    }
}
