<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ProductOptionController
 */
class ProductOptionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $productOptions = ProductOption::factory()->count(3)->create();

        $response = $this->get(route('product-option.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ProductOptionController::class,
            'store',
            \App\Http\Requests\ProductOptionStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $product = Product::factory()->create();

        $response = $this->post(route('product-option.store'), [
            'product_id' => $product->id,
        ]);

        $productOptions = ProductOption::query()
            ->where('product_id', $product->id)
            ->get();
        $this->assertCount(1, $productOptions);
        $productOption = $productOptions->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $productOption = ProductOption::factory()->create();

        $response = $this->get(route('product-option.show', $productOption));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ProductOptionController::class,
            'update',
            \App\Http\Requests\ProductOptionUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $productOption = ProductOption::factory()->create();
        $product = Product::factory()->create();

        $response = $this->put(route('product-option.update', $productOption), [
            'product_id' => $product->id,
        ]);

        $productOption->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($product->id, $productOption->product_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $productOption = ProductOption::factory()->create();

        $response = $this->delete(route('product-option.destroy', $productOption));

        $response->assertNoContent();

        $this->assertModelMissing($productOption);
    }
}
