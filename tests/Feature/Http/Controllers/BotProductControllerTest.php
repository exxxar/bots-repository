<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotProduct;
use App\Models\Owner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotProductController
 */
class BotProductControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botProducts = BotProduct::factory()->count(3)->create();

        $response = $this->get(route('bot-product.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotProductController::class,
            'store',
            \App\Http\Requests\BotProductStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $slug = $this->faker->slug;
        $base_price = $this->faker->randomFloat(/** double_attributes **/);
        $discount_price = $this->faker->randomFloat(/** double_attributes **/);
        $weight = $this->faker->randomFloat(/** double_attributes **/);
        $count = $this->faker->word;
        $in_stock = $this->faker->boolean;
        $owner = Owner::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->post(route('bot-product.store'), [
            'slug' => $slug,
            'base_price' => $base_price,
            'discount_price' => $discount_price,
            'weight' => $weight,
            'count' => $count,
            'in_stock' => $in_stock,
            'owner_id' => $owner->id,
            'bot_id' => $bot->id,
        ]);

        $botProducts = BotProduct::query()
            ->where('slug', $slug)
            ->where('base_price', $base_price)
            ->where('discount_price', $discount_price)
            ->where('weight', $weight)
            ->where('count', $count)
            ->where('in_stock', $in_stock)
            ->where('owner_id', $owner->id)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $botProducts);
        $botProduct = $botProducts->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botProduct = BotProduct::factory()->create();

        $response = $this->get(route('bot-product.show', $botProduct));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotProductController::class,
            'update',
            \App\Http\Requests\BotProductUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botProduct = BotProduct::factory()->create();
        $slug = $this->faker->slug;
        $base_price = $this->faker->randomFloat(/** double_attributes **/);
        $discount_price = $this->faker->randomFloat(/** double_attributes **/);
        $weight = $this->faker->randomFloat(/** double_attributes **/);
        $count = $this->faker->word;
        $in_stock = $this->faker->boolean;
        $owner = Owner::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('bot-product.update', $botProduct), [
            'slug' => $slug,
            'base_price' => $base_price,
            'discount_price' => $discount_price,
            'weight' => $weight,
            'count' => $count,
            'in_stock' => $in_stock,
            'owner_id' => $owner->id,
            'bot_id' => $bot->id,
        ]);

        $botProduct->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($slug, $botProduct->slug);
        $this->assertEquals($base_price, $botProduct->base_price);
        $this->assertEquals($discount_price, $botProduct->discount_price);
        $this->assertEquals($weight, $botProduct->weight);
        $this->assertEquals($count, $botProduct->count);
        $this->assertEquals($in_stock, $botProduct->in_stock);
        $this->assertEquals($owner->id, $botProduct->owner_id);
        $this->assertEquals($bot->id, $botProduct->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botProduct = BotProduct::factory()->create();

        $response = $this->delete(route('bot-product.destroy', $botProduct));

        $response->assertNoContent();

        $this->assertModelMissing($botProduct);
    }
}
