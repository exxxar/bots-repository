<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\CashBackHistory;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CashBackHistoryController
 */
class CashBackHistoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $cashBackHistories = CashBackHistory::factory()->count(3)->create();

        $response = $this->get(route('cash-back-history.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CashBackHistoryController::class,
            'store',
            \App\Http\Requests\CashBackHistoryStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $money_in_check = $this->faker->randomFloat(/** double_attributes **/);
        $operation_type = $this->faker->numberBetween(-10000, 10000);
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $employee = Employee::factory()->create();

        $response = $this->post(route('cash-back-history.store'), [
            'money_in_check' => $money_in_check,
            'operation_type' => $operation_type,
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'employee_id' => $employee->id,
        ]);

        $cashBackHistories = CashBackHistory::query()
            ->where('money_in_check', $money_in_check)
            ->where('operation_type', $operation_type)
            ->where('user_id', $user->id)
            ->where('bot_id', $bot->id)
            ->where('employee_id', $employee->id)
            ->get();
        $this->assertCount(1, $cashBackHistories);
        $cashBackHistory = $cashBackHistories->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $cashBackHistory = CashBackHistory::factory()->create();

        $response = $this->get(route('cash-back-history.show', $cashBackHistory));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CashBackHistoryController::class,
            'update',
            \App\Http\Requests\CashBackHistoryUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $cashBackHistory = CashBackHistory::factory()->create();
        $money_in_check = $this->faker->randomFloat(/** double_attributes **/);
        $operation_type = $this->faker->numberBetween(-10000, 10000);
        $user = User::factory()->create();
        $bot = Bot::factory()->create();
        $employee = Employee::factory()->create();

        $response = $this->put(route('cash-back-history.update', $cashBackHistory), [
            'money_in_check' => $money_in_check,
            'operation_type' => $operation_type,
            'user_id' => $user->id,
            'bot_id' => $bot->id,
            'employee_id' => $employee->id,
        ]);

        $cashBackHistory->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($money_in_check, $cashBackHistory->money_in_check);
        $this->assertEquals($operation_type, $cashBackHistory->operation_type);
        $this->assertEquals($user->id, $cashBackHistory->user_id);
        $this->assertEquals($bot->id, $cashBackHistory->bot_id);
        $this->assertEquals($employee->id, $cashBackHistory->employee_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $cashBackHistory = CashBackHistory::factory()->create();

        $response = $this->delete(route('cash-back-history.destroy', $cashBackHistory));

        $response->assertNoContent();

        $this->assertModelMissing($cashBackHistory);
    }
}
