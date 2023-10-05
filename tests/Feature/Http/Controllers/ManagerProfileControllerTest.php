<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BotUser;
use App\Models\ManagerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ManagerProfileController
 */
class ManagerProfileControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $managerProfiles = ManagerProfile::factory()->count(3)->create();

        $response = $this->get(route('manager-profile.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ManagerProfileController::class,
            'store',
            \App\Http\Requests\ManagerProfileStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot_user = BotUser::factory()->create();
        $stable_personal_discount = $this->faker->randomFloat(/** double_attributes **/);
        $permanent_personal_discount = $this->faker->randomFloat(/** double_attributes **/);
        $max_company_slot_count = $this->faker->numberBetween(-10000, 10000);
        $max_bot_slot_count = $this->faker->numberBetween(-10000, 10000);
        $balance = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('manager-profile.store'), [
            'bot_user_id' => $bot_user->id,
            'stable_personal_discount' => $stable_personal_discount,
            'permanent_personal_discount' => $permanent_personal_discount,
            'max_company_slot_count' => $max_company_slot_count,
            'max_bot_slot_count' => $max_bot_slot_count,
            'balance' => $balance,
        ]);

        $managerProfiles = ManagerProfile::query()
            ->where('bot_user_id', $bot_user->id)
            ->where('stable_personal_discount', $stable_personal_discount)
            ->where('permanent_personal_discount', $permanent_personal_discount)
            ->where('max_company_slot_count', $max_company_slot_count)
            ->where('max_bot_slot_count', $max_bot_slot_count)
            ->where('balance', $balance)
            ->get();
        $this->assertCount(1, $managerProfiles);
        $managerProfile = $managerProfiles->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $managerProfile = ManagerProfile::factory()->create();

        $response = $this->get(route('manager-profile.show', $managerProfile));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ManagerProfileController::class,
            'update',
            \App\Http\Requests\ManagerProfileUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $managerProfile = ManagerProfile::factory()->create();
        $bot_user = BotUser::factory()->create();
        $stable_personal_discount = $this->faker->randomFloat(/** double_attributes **/);
        $permanent_personal_discount = $this->faker->randomFloat(/** double_attributes **/);
        $max_company_slot_count = $this->faker->numberBetween(-10000, 10000);
        $max_bot_slot_count = $this->faker->numberBetween(-10000, 10000);
        $balance = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('manager-profile.update', $managerProfile), [
            'bot_user_id' => $bot_user->id,
            'stable_personal_discount' => $stable_personal_discount,
            'permanent_personal_discount' => $permanent_personal_discount,
            'max_company_slot_count' => $max_company_slot_count,
            'max_bot_slot_count' => $max_bot_slot_count,
            'balance' => $balance,
        ]);

        $managerProfile->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot_user->id, $managerProfile->bot_user_id);
        $this->assertEquals($stable_personal_discount, $managerProfile->stable_personal_discount);
        $this->assertEquals($permanent_personal_discount, $managerProfile->permanent_personal_discount);
        $this->assertEquals($max_company_slot_count, $managerProfile->max_company_slot_count);
        $this->assertEquals($max_bot_slot_count, $managerProfile->max_bot_slot_count);
        $this->assertEquals($balance, $managerProfile->balance);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $managerProfile = ManagerProfile::factory()->create();

        $response = $this->delete(route('manager-profile.destroy', $managerProfile));

        $response->assertNoContent();

        $this->assertModelMissing($managerProfile);
    }
}
