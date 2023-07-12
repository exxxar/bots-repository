<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Order;
use App\Models\ReceiverGet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\OrderController
 */
class OrderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->get(route('order.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\OrderController::class,
            'store',
            \App\Http\Requests\OrderStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $user = User::factory()->create();
        $product_count = $this->faker->numberBetween(-10000, 10000);
        $summary_price = $this->faker->randomFloat(/** double_attributes **/);
        $delivery_price = $this->faker->randomFloat(/** double_attributes **/);
        $delivery_range = $this->faker->randomFloat(/** double_attributes **/);
        $deliveryman_latitude = $this->faker->randomFloat(/** double_attributes **/);
        $deliveryman_longitude = $this->faker->randomFloat(/** double_attributes **/);
        $receiver_get = ReceiverGet::factory()->create();
        $status = $this->faker->numberBetween(-10000, 10000);
        $order_type = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('order.store'), [
            'bot_id' => $bot->id,
            'user_id' => $user->id,
            'product_count' => $product_count,
            'summary_price' => $summary_price,
            'delivery_price' => $delivery_price,
            'delivery_range' => $delivery_range,
            'deliveryman_latitude' => $deliveryman_latitude,
            'deliveryman_longitude' => $deliveryman_longitude,
            'receiver_get_id' => $receiver_get->id,
            'status' => $status,
            'order_type' => $order_type,
        ]);

        $orders = Order::query()
            ->where('bot_id', $bot->id)
            ->where('user_id', $user->id)
            ->where('product_count', $product_count)
            ->where('summary_price', $summary_price)
            ->where('delivery_price', $delivery_price)
            ->where('delivery_range', $delivery_range)
            ->where('deliveryman_latitude', $deliveryman_latitude)
            ->where('deliveryman_longitude', $deliveryman_longitude)
            ->where('receiver_get_id', $receiver_get->id)
            ->where('status', $status)
            ->where('order_type', $order_type)
            ->get();
        $this->assertCount(1, $orders);
        $order = $orders->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('order.show', $order));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\OrderController::class,
            'update',
            \App\Http\Requests\OrderUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $order = Order::factory()->create();
        $bot = Bot::factory()->create();
        $user = User::factory()->create();
        $product_count = $this->faker->numberBetween(-10000, 10000);
        $summary_price = $this->faker->randomFloat(/** double_attributes **/);
        $delivery_price = $this->faker->randomFloat(/** double_attributes **/);
        $delivery_range = $this->faker->randomFloat(/** double_attributes **/);
        $deliveryman_latitude = $this->faker->randomFloat(/** double_attributes **/);
        $deliveryman_longitude = $this->faker->randomFloat(/** double_attributes **/);
        $receiver_get = ReceiverGet::factory()->create();
        $status = $this->faker->numberBetween(-10000, 10000);
        $order_type = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('order.update', $order), [
            'bot_id' => $bot->id,
            'user_id' => $user->id,
            'product_count' => $product_count,
            'summary_price' => $summary_price,
            'delivery_price' => $delivery_price,
            'delivery_range' => $delivery_range,
            'deliveryman_latitude' => $deliveryman_latitude,
            'deliveryman_longitude' => $deliveryman_longitude,
            'receiver_get_id' => $receiver_get->id,
            'status' => $status,
            'order_type' => $order_type,
        ]);

        $order->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $order->bot_id);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($product_count, $order->product_count);
        $this->assertEquals($summary_price, $order->summary_price);
        $this->assertEquals($delivery_price, $order->delivery_price);
        $this->assertEquals($delivery_range, $order->delivery_range);
        $this->assertEquals($deliveryman_latitude, $order->deliveryman_latitude);
        $this->assertEquals($deliveryman_longitude, $order->deliveryman_longitude);
        $this->assertEquals($receiver_get->id, $order->receiver_get_id);
        $this->assertEquals($status, $order->status);
        $this->assertEquals($order_type, $order->order_type);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $order = Order::factory()->create();

        $response = $this->delete(route('order.destroy', $order));

        $response->assertNoContent();

        $this->assertModelMissing($order);
    }
}
