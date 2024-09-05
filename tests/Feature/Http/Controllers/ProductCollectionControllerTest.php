<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BotUser;
use App\Models\ProductCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Bots\Web\ProductCollectionController
 */
final class ProductCollectionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $productCollections = ProductCollection::factory()->count(3)->create();

        $response = $this->get(route('product-collections.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\ProductCollectionController::class,
            'store',
            \App\Http\Requests\ProductCollectionStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $is_public = $this->faker->boolean();
        $is_active = $this->faker->boolean();
        $discount = $this->faker->numberBetween(-10000, 10000);
        $order_position = $this->faker->numberBetween(-10000, 10000);
        $bot_user = BotUser::factory()->create();

        $response = $this->post(route('product-collections.store'), [
            'is_public' => $is_public,
            'is_active' => $is_active,
            'discount' => $discount,
            'order_position' => $order_position,
            'bot_user_id' => $bot_user->id,
        ]);

        $productCollections = ProductCollection::query()
            ->where('is_public', $is_public)
            ->where('is_active', $is_active)
            ->where('discount', $discount)
            ->where('order_position', $order_position)
            ->where('bot_user_id', $bot_user->id)
            ->get();
        $this->assertCount(1, $productCollections);
        $productCollection = $productCollections->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $productCollection = ProductCollection::factory()->create();

        $response = $this->get(route('product-collections.show', $productCollection));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Bots\Web\ProductCollectionController::class,
            'update',
            \App\Http\Requests\ProductCollectionUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $productCollection = ProductCollection::factory()->create();
        $is_public = $this->faker->boolean();
        $is_active = $this->faker->boolean();
        $discount = $this->faker->numberBetween(-10000, 10000);
        $order_position = $this->faker->numberBetween(-10000, 10000);
        $bot_user = BotUser::factory()->create();

        $response = $this->put(route('product-collections.update', $productCollection), [
            'is_public' => $is_public,
            'is_active' => $is_active,
            'discount' => $discount,
            'order_position' => $order_position,
            'bot_user_id' => $bot_user->id,
        ]);

        $productCollection->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($is_public, $productCollection->is_public);
        $this->assertEquals($is_active, $productCollection->is_active);
        $this->assertEquals($discount, $productCollection->discount);
        $this->assertEquals($order_position, $productCollection->order_position);
        $this->assertEquals($bot_user->id, $productCollection->bot_user_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $productCollection = ProductCollection::factory()->create();

        $response = $this->delete(route('product-collections.destroy', $productCollection));

        $response->assertNoContent();

        $this->assertModelMissing($productCollection);
    }
}
