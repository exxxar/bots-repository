<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Company;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LocationController
 */
class LocationControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $locations = Location::factory()->count(3)->create();

        $response = $this->get(route('location.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\LocationController::class,
            'store',
            \App\Http\Requests\LocationStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $lat = $this->faker->latitude;
        $lon = $this->faker->randomFloat(/** double_attributes **/);
        $company = Company::factory()->create();
        $is_active = $this->faker->boolean;
        $can_booking = $this->faker->boolean;

        $response = $this->post(route('location.store'), [
            'lat' => $lat,
            'lon' => $lon,
            'company_id' => $company->id,
            'is_active' => $is_active,
            'can_booking' => $can_booking,
        ]);

        $locations = Location::query()
            ->where('lat', $lat)
            ->where('lon', $lon)
            ->where('company_id', $company->id)
            ->where('is_active', $is_active)
            ->where('can_booking', $can_booking)
            ->get();
        $this->assertCount(1, $locations);
        $location = $locations->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $location = Location::factory()->create();

        $response = $this->get(route('location.show', $location));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\LocationController::class,
            'update',
            \App\Http\Requests\LocationUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $location = Location::factory()->create();
        $lat = $this->faker->latitude;
        $lon = $this->faker->randomFloat(/** double_attributes **/);
        $company = Company::factory()->create();
        $is_active = $this->faker->boolean;
        $can_booking = $this->faker->boolean;

        $response = $this->put(route('location.update', $location), [
            'lat' => $lat,
            'lon' => $lon,
            'company_id' => $company->id,
            'is_active' => $is_active,
            'can_booking' => $can_booking,
        ]);

        $location->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($lat, $location->lat);
        $this->assertEquals($lon, $location->lon);
        $this->assertEquals($company->id, $location->company_id);
        $this->assertEquals($is_active, $location->is_active);
        $this->assertEquals($can_booking, $location->can_booking);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $location = Location::factory()->create();

        $response = $this->delete(route('location.destroy', $location));

        $response->assertNoContent();

        $this->assertModelMissing($location);
    }
}
