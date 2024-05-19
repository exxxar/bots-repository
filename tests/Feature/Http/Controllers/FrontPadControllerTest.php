<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\FrontPad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FrontPadController
 */
class FrontPadControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $frontPads = FrontPad::factory()->count(3)->create();

        $response = $this->get(route('front-pad.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FrontPadController::class,
            'store',
            \App\Http\Requests\FrontPadStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $response = $this->post(route('front-pad.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(frontPads, [ /* ... */ ]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $frontPad = FrontPad::factory()->create();

        $response = $this->get(route('front-pad.show', $frontPad));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FrontPadController::class,
            'update',
            \App\Http\Requests\FrontPadUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $frontPad = FrontPad::factory()->create();

        $response = $this->put(route('front-pad.update', $frontPad));

        $frontPad->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $frontPad = FrontPad::factory()->create();

        $response = $this->delete(route('front-pad.destroy', $frontPad));

        $response->assertNoContent();

        $this->assertModelMissing($frontPad);
    }
}
