<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Iiko;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Bots\Web\IikoController
 */
final class IikoControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $iikos = Iiko::factory()->count(3)->create();

        $response = $this->get(route('iikos.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\IikoController::class,
            'store',
            \App\Http\Requests\IikoStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $response = $this->post(route('iikos.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(iikos, [ /* ... */ ]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $iiko = Iiko::factory()->create();

        $response = $this->get(route('iikos.show', $iiko));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\IikoController::class,
            'update',
            \App\Http\Requests\IikoUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $iiko = Iiko::factory()->create();

        $response = $this->put(route('iikos.update', $iiko));

        $iiko->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $iiko = Iiko::factory()->create();

        $response = $this->delete(route('iikos.destroy', $iiko));

        $response->assertNoContent();

        $this->assertModelMissing($iiko);
    }
}
