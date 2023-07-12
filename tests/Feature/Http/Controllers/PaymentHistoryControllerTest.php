<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PaymentHistoryController
 */
class PaymentHistoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $paymentHistories = PaymentHistory::factory()->count(3)->create();

        $response = $this->get(route('payment-history.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PaymentHistoryController::class,
            'store',
            \App\Http\Requests\PaymentHistoryStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $amount = $this->faker->randomFloat(/** double_attributes **/);
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $response = $this->post(route('payment-history.store'), [
            'amount' => $amount,
            'user_id' => $user->id,
            'order_id' => $order->id,
        ]);

        $paymentHistories = PaymentHistory::query()
            ->where('amount', $amount)
            ->where('user_id', $user->id)
            ->where('order_id', $order->id)
            ->get();
        $this->assertCount(1, $paymentHistories);
        $paymentHistory = $paymentHistories->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $paymentHistory = PaymentHistory::factory()->create();

        $response = $this->get(route('payment-history.show', $paymentHistory));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PaymentHistoryController::class,
            'update',
            \App\Http\Requests\PaymentHistoryUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $paymentHistory = PaymentHistory::factory()->create();
        $amount = $this->faker->randomFloat(/** double_attributes **/);
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $response = $this->put(route('payment-history.update', $paymentHistory), [
            'amount' => $amount,
            'user_id' => $user->id,
            'order_id' => $order->id,
        ]);

        $paymentHistory->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($amount, $paymentHistory->amount);
        $this->assertEquals($user->id, $paymentHistory->user_id);
        $this->assertEquals($order->id, $paymentHistory->order_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $paymentHistory = PaymentHistory::factory()->create();

        $response = $this->delete(route('payment-history.destroy', $paymentHistory));

        $response->assertNoContent();

        $this->assertModelMissing($paymentHistory);
    }
}
