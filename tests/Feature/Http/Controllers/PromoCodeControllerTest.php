<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\PromoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PromoCodeController
 */
class PromoCodeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $promoCodes = PromoCode::factory()->count(3)->create();

        $response = $this->get(route('promo-code.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PromoCodeController::class,
            'store',
            \App\Http\Requests\PromoCodeStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $code = $this->faker->word;
        $slot_amount = $this->faker->numberBetween(-10000, 10000);
        $cashback_amount = $this->faker->randomFloat(/** double_attributes **/);
        $max_activation_count = $this->faker->numberBetween(-10000, 10000);
        $is_active = $this->faker->boolean;

        $response = $this->post(route('promo-code.store'), [
            'bot_id' => $bot->id,
            'code' => $code,
            'slot_amount' => $slot_amount,
            'cashback_amount' => $cashback_amount,
            'max_activation_count' => $max_activation_count,
            'is_active' => $is_active,
        ]);

        $promoCodes = PromoCode::query()
            ->where('bot_id', $bot->id)
            ->where('code', $code)
            ->where('slot_amount', $slot_amount)
            ->where('cashback_amount', $cashback_amount)
            ->where('max_activation_count', $max_activation_count)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $promoCodes);
        $promoCode = $promoCodes->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $promoCode = PromoCode::factory()->create();

        $response = $this->get(route('promo-code.show', $promoCode));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\PromoCodeController::class,
            'update',
            \App\Http\Requests\PromoCodeUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $promoCode = PromoCode::factory()->create();
        $bot = Bot::factory()->create();
        $code = $this->faker->word;
        $slot_amount = $this->faker->numberBetween(-10000, 10000);
        $cashback_amount = $this->faker->randomFloat(/** double_attributes **/);
        $max_activation_count = $this->faker->numberBetween(-10000, 10000);
        $is_active = $this->faker->boolean;

        $response = $this->put(route('promo-code.update', $promoCode), [
            'bot_id' => $bot->id,
            'code' => $code,
            'slot_amount' => $slot_amount,
            'cashback_amount' => $cashback_amount,
            'max_activation_count' => $max_activation_count,
            'is_active' => $is_active,
        ]);

        $promoCode->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $promoCode->bot_id);
        $this->assertEquals($code, $promoCode->code);
        $this->assertEquals($slot_amount, $promoCode->slot_amount);
        $this->assertEquals($cashback_amount, $promoCode->cashback_amount);
        $this->assertEquals($max_activation_count, $promoCode->max_activation_count);
        $this->assertEquals($is_active, $promoCode->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $promoCode = PromoCode::factory()->create();

        $response = $this->delete(route('promo-code.destroy', $promoCode));

        $response->assertNoContent();

        $this->assertModelMissing($promoCode);
    }
}
