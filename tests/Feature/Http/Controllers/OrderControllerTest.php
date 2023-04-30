<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
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
        $status = $this->faker->numberBetween(-10000, 10000);
        $need_delivery = $this->faker->boolean;
        $summary_price = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->post(route('order.store'), [
            'status' => $status,
            'need_delivery' => $need_delivery,
            'summary_price' => $summary_price,
        ]);

        $orders = Order::query()
            ->where('status', $status)
            ->where('need_delivery', $need_delivery)
            ->where('summary_price', $summary_price)
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
        $status = $this->faker->numberBetween(-10000, 10000);
        $need_delivery = $this->faker->boolean;
        $summary_price = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->put(route('order.update', $order), [
            'status' => $status,
            'need_delivery' => $need_delivery,
            'summary_price' => $summary_price,
        ]);

        $order->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($status, $order->status);
        $this->assertEquals($need_delivery, $order->need_delivery);
        $this->assertEquals($summary_price, $order->summary_price);
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
