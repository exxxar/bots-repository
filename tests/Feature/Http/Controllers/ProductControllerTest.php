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
        $bot = Bot::factory()->create();
        $title = $this->faker->sentence(4);
        $weight = $this->faker->randomFloat(/** double_attributes **/);
        $base_price_before_discount = $this->faker->randomFloat(/** double_attributes **/);
        $base_price = $this->faker->randomFloat(/** double_attributes **/);
        $portion_count = $this->faker->numberBetween(-10000, 10000);
        $is_active = $this->faker->boolean;

        $response = $this->post(route('product.store'), [
            'bot_id' => $bot->id,
            'title' => $title,
            'weight' => $weight,
            'base_price_before_discount' => $base_price_before_discount,
            'base_price' => $base_price,
            'portion_count' => $portion_count,
            'is_active' => $is_active,
        ]);

        $products = Product::query()
            ->where('bot_id', $bot->id)
            ->where('title', $title)
            ->where('weight', $weight)
            ->where('base_price_before_discount', $base_price_before_discount)
            ->where('base_price', $base_price)
            ->where('portion_count', $portion_count)
            ->where('is_active', $is_active)
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
        $bot = Bot::factory()->create();
        $title = $this->faker->sentence(4);
        $weight = $this->faker->randomFloat(/** double_attributes **/);
        $base_price_before_discount = $this->faker->randomFloat(/** double_attributes **/);
        $base_price = $this->faker->randomFloat(/** double_attributes **/);
        $portion_count = $this->faker->numberBetween(-10000, 10000);
        $is_active = $this->faker->boolean;

        $response = $this->put(route('product.update', $product), [
            'bot_id' => $bot->id,
            'title' => $title,
            'weight' => $weight,
            'base_price_before_discount' => $base_price_before_discount,
            'base_price' => $base_price,
            'portion_count' => $portion_count,
            'is_active' => $is_active,
        ]);

        $product->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $product->bot_id);
        $this->assertEquals($title, $product->title);
        $this->assertEquals($weight, $product->weight);
        $this->assertEquals($base_price_before_discount, $product->base_price_before_discount);
        $this->assertEquals($base_price, $product->base_price);
        $this->assertEquals($portion_count, $product->portion_count);
        $this->assertEquals($is_active, $product->is_active);
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
