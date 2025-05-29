<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StoryController
 */
final class StoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $stories = Story::factory()->count(3)->create();

        $response = $this->get(route('stories.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StoryController::class,
            'store',
            \App\Http\Requests\StoryStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();

        $response = $this->post(route('stories.store'), [
            'bot_id' => $bot->id,
        ]);

        $stories = Story::query()
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $stories);
        $story = $stories->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $story = Story::factory()->create();

        $response = $this->get(route('stories.show', $story));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StoryController::class,
            'update',
            \App\Http\Requests\StoryUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $story = Story::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('stories.update', $story), [
            'bot_id' => $bot->id,
        ]);

        $story->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $story->bot_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $story = Story::factory()->create();

        $response = $this->delete(route('stories.destroy', $story));

        $response->assertNoContent();

        $this->assertModelMissing($story);
    }
}
