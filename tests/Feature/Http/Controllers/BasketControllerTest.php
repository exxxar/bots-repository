<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Bots\Web\BasketController
 */
class BasketControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $baskets = Basket::factory()->count(3)->create();

        $response = $this->get(route('basket.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\BasketController::class,
            'store',
            \App\Http\Requests\BasketStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $product = Product::factory()->create();
        $count = $this->faker->numberBetween(-10000, 10000);
        $bot_user = BotUser::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->post(route('basket.store'), [
            'product_id' => $product->id,
            'count' => $count,
            'bot_user_id' => $bot_user->id,
            'bot_id' => $bot->id,
        ]);

        $baskets = Basket::query()
            ->where('product_id', $product->id)
            ->where('count', $count)
            ->where('bot_user_id', $bot_user->id)
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $baskets);
        $basket = $baskets->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $basket = Basket::factory()->create();

        $response = $this->get(route('basket.show', $basket));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\BasketController::class,
            'update',
            \App\Http\Requests\BasketUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $basket = Basket::factory()->create();
        $product = Product::factory()->create();
        $count = $this->faker->numberBetween(-10000, 10000);
        $bot_user = BotUser::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('basket.update', $basket), [
            'product_id' => $product->id,
            'count' => $count,
            'bot_user_id' => $bot_user->id,
            'bot_id' => $bot->id,
        ]);

        $basket->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($product->id, $basket->product_id);
        $this->assertEquals($count, $basket->count);
        $this->assertEquals($bot_user->id, $basket->bot_user_id);
        $this->assertEquals($bot->id, $basket->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $basket = Basket::factory()->create();

        $response = $this->delete(route('basket.destroy', $basket));

        $response->assertNoContent();

        $this->assertModelMissing($basket);
    }
}
