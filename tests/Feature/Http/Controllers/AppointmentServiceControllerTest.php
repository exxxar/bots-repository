<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AppointmentEvent;
use App\Models\AppointmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AppointmentServiceController
 */
class AppointmentServiceControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $appointmentServices = AppointmentService::factory()->count(3)->create();

        $response = $this->get(route('appointment-service.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentServiceController::class,
            'store',
            \App\Http\Requests\AppointmentServiceStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $appointment_event = AppointmentEvent::factory()->create();
        $price = $this->faker->randomFloat(/** double_attributes **/);
        $discount_price = $this->faker->randomFloat(/** double_attributes **/);
        $need_prepayment = $this->faker->boolean;

        $response = $this->post(route('appointment-service.store'), [
            'appointment_event_id' => $appointment_event->id,
            'price' => $price,
            'discount_price' => $discount_price,
            'need_prepayment' => $need_prepayment,
        ]);

        $appointmentServices = AppointmentService::query()
            ->where('appointment_event_id', $appointment_event->id)
            ->where('price', $price)
            ->where('discount_price', $discount_price)
            ->where('need_prepayment', $need_prepayment)
            ->get();
        $this->assertCount(1, $appointmentServices);
        $appointmentService = $appointmentServices->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $appointmentService = AppointmentService::factory()->create();

        $response = $this->get(route('appointment-service.show', $appointmentService));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentServiceController::class,
            'update',
            \App\Http\Requests\AppointmentServiceUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $appointmentService = AppointmentService::factory()->create();
        $appointment_event = AppointmentEvent::factory()->create();
        $price = $this->faker->randomFloat(/** double_attributes **/);
        $discount_price = $this->faker->randomFloat(/** double_attributes **/);
        $need_prepayment = $this->faker->boolean;

        $response = $this->put(route('appointment-service.update', $appointmentService), [
            'appointment_event_id' => $appointment_event->id,
            'price' => $price,
            'discount_price' => $discount_price,
            'need_prepayment' => $need_prepayment,
        ]);

        $appointmentService->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($appointment_event->id, $appointmentService->appointment_event_id);
        $this->assertEquals($price, $appointmentService->price);
        $this->assertEquals($discount_price, $appointmentService->discount_price);
        $this->assertEquals($need_prepayment, $appointmentService->need_prepayment);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $appointmentService = AppointmentService::factory()->create();

        $response = $this->delete(route('appointment-service.destroy', $appointmentService));

        $response->assertNoContent();

        $this->assertModelMissing($appointmentService);
    }
}
