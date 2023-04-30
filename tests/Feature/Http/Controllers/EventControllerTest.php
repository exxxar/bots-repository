<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\EventController
 */
class EventControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $events = Event::factory()->count(3)->create();

        $response = $this->get(route('event.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\EventController::class,
            'store',
            \App\Http\Requests\EventStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();

        $response = $this->post(route('event.store'), [
            'bot_id' => $bot->id,
        ]);

        $events = Event::query()
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $events);
        $event = $events->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $event = Event::factory()->create();

        $response = $this->get(route('event.show', $event));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\EventController::class,
            'update',
            \App\Http\Requests\EventUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $event = Event::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('event.update', $event), [
            'bot_id' => $bot->id,
        ]);

        $event->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $event->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $event = Event::factory()->create();

        $response = $this->delete(route('event.destroy', $event));

        $response->assertNoContent();

        $this->assertModelMissing($event);
    }
}
