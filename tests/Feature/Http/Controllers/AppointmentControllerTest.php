<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use App\Models\Bot;
use App\Models\BotUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\AppointmentController
 */
class AppointmentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $appointments = Appointment::factory()->count(3)->create();

        $response = $this->get(route('appointment.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\AppointmentController::class,
            'store',
            \App\Http\Requests\AppointmentStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();
        $appointment_schedule = AppointmentSchedule::factory()->create();
        $status = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('appointment.store'), [
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
            'appointment_schedule_id' => $appointment_schedule->id,
            'status' => $status,
        ]);

        $appointments = Appointment::query()
            ->where('bot_id', $bot->id)
            ->where('bot_user_id', $bot_user->id)
            ->where('appointment_schedule_id', $appointment_schedule->id)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $appointments);
        $appointment = $appointments->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->get(route('appointment.show', $appointment));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\AppointmentController::class,
            'update',
            \App\Http\Requests\AppointmentUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $appointment = Appointment::factory()->create();
        $bot = Bot::factory()->create();
        $bot_user = BotUser::factory()->create();
        $appointment_schedule = AppointmentSchedule::factory()->create();
        $status = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('appointment.update', $appointment), [
            'bot_id' => $bot->id,
            'bot_user_id' => $bot_user->id,
            'appointment_schedule_id' => $appointment_schedule->id,
            'status' => $status,
        ]);

        $appointment->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $appointment->bot_id);
        $this->assertEquals($bot_user->id, $appointment->bot_user_id);
        $this->assertEquals($appointment_schedule->id, $appointment->appointment_schedule_id);
        $this->assertEquals($status, $appointment->status);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->delete(route('appointment.destroy', $appointment));

        $response->assertNoContent();

        $this->assertModelMissing($appointment);
    }
}
