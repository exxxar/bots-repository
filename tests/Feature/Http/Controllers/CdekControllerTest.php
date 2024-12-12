<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Cdek;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CdekController
 */
final class CdekControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $cdeks = Cdek::factory()->count(3)->create();

        $response = $this->get(route('cdeks.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CdekController::class,
            'store',
            \App\Http\Requests\CdekStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $is_active = $this->faker->boolean();

        $response = $this->post(route('cdeks.store'), [
            'is_active' => $is_active,
        ]);

        $cdeks = Cdek::query()
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $cdeks);
        $cdek = $cdeks->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $cdek = Cdek::factory()->create();

        $response = $this->get(route('cdeks.show', $cdek));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CdekController::class,
            'update',
            \App\Http\Requests\CdekUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $cdek = Cdek::factory()->create();
        $is_active = $this->faker->boolean();

        $response = $this->put(route('cdeks.update', $cdek), [
            'is_active' => $is_active,
        ]);

        $cdek->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($is_active, $cdek->is_active);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $cdek = Cdek::factory()->create();

        $response = $this->delete(route('cdeks.destroy', $cdek));

        $response->assertNoContent();

        $this->assertModelMissing($cdek);
    }
}
