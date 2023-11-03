<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CashBack;
use App\Models\CashBackSub;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CashBackSubController
 */
class CashBackSubControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $cashBackSubs = CashBackSub::factory()->count(3)->create();

        $response = $this->get(route('cash-back-sub.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CashBackSubController::class,
            'store',
            \App\Http\Requests\CashBackSubStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $cash_back = CashBack::factory()->create();
        $amount = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->post(route('cash-back-sub.store'), [
            'cash_back_id' => $cash_back->id,
            'amount' => $amount,
        ]);

        $cashBackSubs = CashBackSub::query()
            ->where('cash_back_id', $cash_back->id)
            ->where('amount', $amount)
            ->get();
        $this->assertCount(1, $cashBackSubs);
        $cashBackSub = $cashBackSubs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $cashBackSub = CashBackSub::factory()->create();

        $response = $this->get(route('cash-back-sub.show', $cashBackSub));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CashBackSubController::class,
            'update',
            \App\Http\Requests\CashBackSubUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $cashBackSub = CashBackSub::factory()->create();
        $cash_back = CashBack::factory()->create();
        $amount = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->put(route('cash-back-sub.update', $cashBackSub), [
            'cash_back_id' => $cash_back->id,
            'amount' => $amount,
        ]);

        $cashBackSub->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($cash_back->id, $cashBackSub->cash_back_id);
        $this->assertEquals($amount, $cashBackSub->amount);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $cashBackSub = CashBackSub::factory()->create();

        $response = $this->delete(route('cash-back-sub.destroy', $cashBackSub));

        $response->assertNoContent();

        $this->assertModelMissing($cashBackSub);
    }
}
