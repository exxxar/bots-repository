<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AppointmentEvent;
use App\Models\AppointmentReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AppointmentReviewController
 */
class AppointmentReviewControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $appointmentReviews = AppointmentReview::factory()->count(3)->create();

        $response = $this->get(route('appointment-review.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentReviewController::class,
            'store',
            \App\Http\Requests\AppointmentReviewStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $appointment_event = AppointmentEvent::factory()->create();
        $rating = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('appointment-review.store'), [
            'appointment_event_id' => $appointment_event->id,
            'rating' => $rating,
        ]);

        $appointmentReviews = AppointmentReview::query()
            ->where('appointment_event_id', $appointment_event->id)
            ->where('rating', $rating)
            ->get();
        $this->assertCount(1, $appointmentReviews);
        $appointmentReview = $appointmentReviews->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $appointmentReview = AppointmentReview::factory()->create();

        $response = $this->get(route('appointment-review.show', $appointmentReview));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentReviewController::class,
            'update',
            \App\Http\Requests\AppointmentReviewUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $appointmentReview = AppointmentReview::factory()->create();
        $appointment_event = AppointmentEvent::factory()->create();
        $rating = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('appointment-review.update', $appointmentReview), [
            'appointment_event_id' => $appointment_event->id,
            'rating' => $rating,
        ]);

        $appointmentReview->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($appointment_event->id, $appointmentReview->appointment_event_id);
        $this->assertEquals($rating, $appointmentReview->rating);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $appointmentReview = AppointmentReview::factory()->create();

        $response = $this->delete(route('appointment-review.destroy', $appointmentReview));

        $response->assertNoContent();

        $this->assertModelMissing($appointmentReview);
    }
}
