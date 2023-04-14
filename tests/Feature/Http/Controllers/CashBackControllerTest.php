<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\CashBack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CashBackController
 */
class CashBackControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $cashBacks = CashBack::factory()->count(3)->create();

        $response = $this->get(route('cash-back.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CashBackController::class,
            'store',
            \App\Http\Requests\CashBackStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $amount = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->post(route('cash-back.store'), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'amount' => $amount,
        ]);

        $cashBacks = CashBack::query()
            ->where('user_id', $user->id)
            ->where('bot_id', $bot->id)
            ->where('amount', $amount)
            ->get();
        $this->assertCount(1, $cashBacks);
        $cashBack = $cashBacks->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $cashBack = CashBack::factory()->create();

        $response = $this->get(route('cash-back.show', $cashBack));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CashBackController::class,
            'update',
            \App\Http\Requests\CashBackUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $cashBack = CashBack::factory()->create();
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $amount = $this->faker->randomFloat(/** double_attributes **/);

        $response = $this->put(route('cash-back.update', $cashBack), [
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'amount' => $amount,
        ]);

        $cashBack->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $cashBack->user_id);
        $this->assertEquals($bot->id, $cashBack->bot_id);
        $this->assertEquals($amount, $cashBack->amount);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $cashBack = CashBack::factory()->create();

        $response = $this->delete(route('cash-back.destroy', $cashBack));

        $response->assertNoContent();

        $this->assertModelMissing($cashBack);
    }
}
