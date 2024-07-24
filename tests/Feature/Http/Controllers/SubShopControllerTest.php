<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SubShop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SubShopController
 */
final class SubShopControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $subShops = SubShop::factory()->count(3)->create();

        $response = $this->get(route('sub-shops.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SubShopController::class,
            'store',
            \App\Http\Requests\SubShopStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $is_active = $this->faker->boolean();

        $response = $this->post(route('sub-shops.store'), [
            'is_active' => $is_active,
        ]);

        $subShops = SubShop::query()
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $subShops);
        $subShop = $subShops->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $subShop = SubShop::factory()->create();

        $response = $this->get(route('sub-shops.show', $subShop));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SubShopController::class,
            'update',
            \App\Http\Requests\SubShopUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $subShop = SubShop::factory()->create();
        $is_active = $this->faker->boolean();

        $response = $this->put(route('sub-shops.update', $subShop), [
            'is_active' => $is_active,
        ]);

        $subShop->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($is_active, $subShop->is_active);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $subShop = SubShop::factory()->create();

        $response = $this->delete(route('sub-shops.destroy', $subShop));

        $response->assertNoContent();

        $this->assertModelMissing($subShop);
    }
}
