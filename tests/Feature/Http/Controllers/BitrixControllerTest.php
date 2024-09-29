<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bitrix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Bots\Web\BitrixController
 */
final class BitrixControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $bitrixes = Bitrix::factory()->count(3)->create();

        $response = $this->get(route('bitrixes.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\BitrixController::class,
            'store',
            \App\Http\Requests\BitrixStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $response = $this->post(route('bitrixes.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(bitrixes, [ /* ... */ ]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $bitrix = Bitrix::factory()->create();

        $response = $this->get(route('bitrixes.show', $bitrix));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\BitrixController::class,
            'update',
            \App\Http\Requests\BitrixUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $bitrix = Bitrix::factory()->create();

        $response = $this->put(route('bitrixes.update', $bitrix));

        $bitrix->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $bitrix = Bitrix::factory()->create();

        $response = $this->delete(route('bitrixes.destroy', $bitrix));

        $response->assertNoContent();

        $this->assertModelMissing($bitrix);
    }
}
