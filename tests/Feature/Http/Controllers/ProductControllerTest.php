<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ProductController
 */
class ProductControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('product.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ProductController::class,
            'store',
            \App\Http\Requests\ProductStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $type = $this->faker->numberBetween(-10000, 10000);
        $old_price = $this->faker->randomFloat(/** double_attributes **/);
        $current_price = $this->faker->randomFloat(/** double_attributes **/);
        $bot = Bot::factory()->create();

        $response = $this->post(route('product.store'), [
            'type' => $type,
            'old_price' => $old_price,
            'current_price' => $current_price,
            'bot_id' => $bot->id,
        ]);

        $products = Product::query()
            ->where('type', $type)
            ->where('old_price', $old_price)
            ->where('current_price', $current_price)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $products);
        $product = $products->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.show', $product));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\ProductController::class,
            'update',
            \App\Http\Requests\ProductUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $product = Product::factory()->create();
        $type = $this->faker->numberBetween(-10000, 10000);
        $old_price = $this->faker->randomFloat(/** double_attributes **/);
        $current_price = $this->faker->randomFloat(/** double_attributes **/);
        $bot = Bot::factory()->create();

        $response = $this->put(route('product.update', $product), [
            'type' => $type,
            'old_price' => $old_price,
            'current_price' => $current_price,
            'bot_id' => $bot->id,
        ]);

        $product->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($type, $product->type);
        $this->assertEquals($old_price, $product->old_price);
        $this->assertEquals($current_price, $product->current_price);
        $this->assertEquals($bot->id, $product->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('product.destroy', $product));

        $response->assertNoContent();

        $this->assertModelMissing($product);
    }
}
