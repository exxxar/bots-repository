<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AppointmentEvent;
use App\Models\AppointmentSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AppointmentScheduleController
 */
class AppointmentScheduleControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $appointmentSchedules = AppointmentSchedule::factory()->count(3)->create();

        $response = $this->get(route('appointment-schedule.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentScheduleController::class,
            'store',
            \App\Http\Requests\AppointmentScheduleStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $appointment_event = AppointmentEvent::factory()->create();
        $day = $this->faker->word;

        $response = $this->post(route('appointment-schedule.store'), [
            'appointment_event_id' => $appointment_event->id,
            'day' => $day,
        ]);

        $appointmentSchedules = AppointmentSchedule::query()
            ->where('appointment_event_id', $appointment_event->id)
            ->where('day', $day)
            ->get();
        $this->assertCount(1, $appointmentSchedules);
        $appointmentSchedule = $appointmentSchedules->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $appointmentSchedule = AppointmentSchedule::factory()->create();

        $response = $this->get(route('appointment-schedule.show', $appointmentSchedule));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentScheduleController::class,
            'update',
            \App\Http\Requests\AppointmentScheduleUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $appointmentSchedule = AppointmentSchedule::factory()->create();
        $appointment_event = AppointmentEvent::factory()->create();
        $day = $this->faker->word;

        $response = $this->put(route('appointment-schedule.update', $appointmentSchedule), [
            'appointment_event_id' => $appointment_event->id,
            'day' => $day,
        ]);

        $appointmentSchedule->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($appointment_event->id, $appointmentSchedule->appointment_event_id);
        $this->assertEquals($day, $appointmentSchedule->day);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $appointmentSchedule = AppointmentSchedule::factory()->create();

        $response = $this->delete(route('appointment-schedule.destroy', $appointmentSchedule));

        $response->assertNoContent();

        $this->assertModelMissing($appointmentSchedule);
    }
}
