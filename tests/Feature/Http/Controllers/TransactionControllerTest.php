<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TransactionController
 */
class TransactionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $transactions = Transaction::factory()->count(3)->create();

        $response = $this->get(route('transaction.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TransactionController::class,
            'store',
            \App\Http\Requests\TransactionStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $payload = $this->faker->word;
        $currency = $this->faker->word;
        $total_amount = $this->faker->numberBetween(-10000, 10000);
        $status = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('transaction.store'), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $total_amount,
            'status' => $status,
        ]);

        $transactions = Transaction::query()
            ->where('user_id', $user->id)
            ->where('bot_id', $bot->id)
            ->where('payload', $payload)
            ->where('currency', $currency)
            ->where('total_amount', $total_amount)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $transactions);
        $transaction = $transactions->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $transaction = Transaction::factory()->create();

        $response = $this->get(route('transaction.show', $transaction));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TransactionController::class,
            'update',
            \App\Http\Requests\TransactionUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $transaction = Transaction::factory()->create();
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $payload = $this->faker->word;
        $currency = $this->faker->word;
        $total_amount = $this->faker->numberBetween(-10000, 10000);
        $status = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('transaction.update', $transaction), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $total_amount,
            'status' => $status,
        ]);

        $transaction->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertEquals($bot->id, $transaction->bot_id);
        $this->assertEquals($payload, $transaction->payload);
        $this->assertEquals($currency, $transaction->currency);
        $this->assertEquals($total_amount, $transaction->total_amount);
        $this->assertEquals($status, $transaction->status);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $transaction = Transaction::factory()->create();

        $response = $this->delete(route('transaction.destroy', $transaction));

        $response->assertNoContent();

        $this->assertModelMissing($transaction);
    }
}
