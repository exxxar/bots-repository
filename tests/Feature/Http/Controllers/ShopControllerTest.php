<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ShopController
 */
final class ShopControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $shops = Shop::factory()->count(3)->create();

        $response = $this->get(route('shops.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ShopController::class,
            'store',
            \App\Http\Requests\ShopStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $is_default = $this->faker->boolean();

        $response = $this->post(route('shops.store'), [
            'is_default' => $is_default,
        ]);

        $shops = Shop::query()
            ->where('is_default', $is_default)
            ->get();
        $this->assertCount(1, $shops);
        $shop = $shops->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $shop = Shop::factory()->create();

        $response = $this->get(route('shops.show', $shop));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ShopController::class,
            'update',
            \App\Http\Requests\ShopUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $shop = Shop::factory()->create();
        $is_default = $this->faker->boolean();

        $response = $this->put(route('shops.update', $shop), [
            'is_default' => $is_default,
        ]);

        $shop->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($is_default, $shop->is_default);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $shop = Shop::factory()->create();

        $response = $this->delete(route('shops.destroy', $shop));

        $response->assertNoContent();

        $this->assertModelMissing($shop);
    }
}
