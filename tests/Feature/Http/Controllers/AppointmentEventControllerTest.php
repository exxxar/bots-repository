<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AppointmentEvent;
use App\Models\Bot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AppointmentEventController
 */
class AppointmentEventControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $appointmentEvents = AppointmentEvent::factory()->count(3)->create();

        $response = $this->get(route('appointment-event.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentEventController::class,
            'store',
            \App\Http\Requests\AppointmentEventStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $is_group = $this->faker->boolean;

        $response = $this->post(route('appointment-event.store'), [
            'bot_id' => $bot->id,
            'is_group' => $is_group,
        ]);

        $appointmentEvents = AppointmentEvent::query()
            ->where('bot_id', $bot->id)
            ->where('is_group', $is_group)
            ->get();
        $this->assertCount(1, $appointmentEvents);
        $appointmentEvent = $appointmentEvents->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $appointmentEvent = AppointmentEvent::factory()->create();

        $response = $this->get(route('appointment-event.show', $appointmentEvent));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentEventController::class,
            'update',
            \App\Http\Requests\AppointmentEventUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $appointmentEvent = AppointmentEvent::factory()->create();
        $bot = Bot::factory()->create();
        $is_group = $this->faker->boolean;

        $response = $this->put(route('appointment-event.update', $appointmentEvent), [
            'bot_id' => $bot->id,
            'is_group' => $is_group,
        ]);

        $appointmentEvent->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $appointmentEvent->bot_id);
        $this->assertEquals($is_group, $appointmentEvent->is_group);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $appointmentEvent = AppointmentEvent::factory()->create();

        $response = $this->delete(route('appointment-event.destroy', $appointmentEvent));

        $response->assertNoContent();

        $this->assertModelMissing($appointmentEvent);
    }
}
