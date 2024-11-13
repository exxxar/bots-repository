<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TrafficSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TrafficSourceController
 */
final class TrafficSourceControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $trafficSources = TrafficSource::factory()->count(3)->create();

        $response = $this->get(route('traffic-sources.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TrafficSourceController::class,
            'store',
            \App\Http\Requests\TrafficSourceStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $response = $this->post(route('traffic-sources.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(trafficSources, [ /* ... */ ]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $trafficSource = TrafficSource::factory()->create();

        $response = $this->get(route('traffic-sources.show', $trafficSource));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TrafficSourceController::class,
            'update',
            \App\Http\Requests\TrafficSourceUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $trafficSource = TrafficSource::factory()->create();

        $response = $this->put(route('traffic-sources.update', $trafficSource));

        $trafficSource->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $trafficSource = TrafficSource::factory()->create();

        $response = $this->delete(route('traffic-sources.destroy', $trafficSource));

        $response->assertNoContent();

        $this->assertModelMissing($trafficSource);
    }
}
