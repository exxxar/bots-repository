<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotType;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\BotController
 */
class BotControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $bots = Bot::factory()->count(3)->create();

        $response = $this->get(route('bot.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotController::class,
            'store',
            \App\Http\Requests\BotStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $company = Company::factory()->create();
        $bot_domain = $this->faker->word;
        $balance = $this->faker->randomFloat(/** double_attributes **/);
        $tax_per_day = $this->faker->randomFloat(/** double_attributes **/);
        $is_active = $this->faker->boolean;
        $bot_type = BotType::factory()->create();

        $response = $this->post(route('bot.store'), [
            'company_id' => $company->id,
            'bot_domain' => $bot_domain,
            'balance' => $balance,
            'tax_per_day' => $tax_per_day,
            'is_active' => $is_active,
            'bot_type_id' => $bot_type->id,
        ]);

        $bots = Bot::query()
            ->where('company_id', $company->id)
            ->where('bot_domain', $bot_domain)
            ->where('balance', $balance)
            ->where('tax_per_day', $tax_per_day)
            ->where('is_active', $is_active)
            ->where('bot_type_id', $bot_type->id)
            ->get();
        $this->assertCount(1, $bots);
        $bot = $bots->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $bot = Bot::factory()->create();

        $response = $this->get(route('bot.show', $bot));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Admin\BotController::class,
            'update',
            \App\Http\Requests\BotUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $bot = Bot::factory()->create();
        $company = Company::factory()->create();
        $bot_domain = $this->faker->word;
        $balance = $this->faker->randomFloat(/** double_attributes **/);
        $tax_per_day = $this->faker->randomFloat(/** double_attributes **/);
        $is_active = $this->faker->boolean;
        $bot_type = BotType::factory()->create();

        $response = $this->put(route('bot.update', $bot), [
            'company_id' => $company->id,
            'bot_domain' => $bot_domain,
            'balance' => $balance,
            'tax_per_day' => $tax_per_day,
            'is_active' => $is_active,
            'bot_type_id' => $bot_type->id,
        ]);

        $bot->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($company->id, $bot->company_id);
        $this->assertEquals($bot_domain, $bot->bot_domain);
        $this->assertEquals($balance, $bot->balance);
        $this->assertEquals($tax_per_day, $bot->tax_per_day);
        $this->assertEquals($is_active, $bot->is_active);
        $this->assertEquals($bot_type->id, $bot->bot_type_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $bot = Bot::factory()->create();

        $response = $this->delete(route('bot.destroy', $bot));

        $response->assertNoContent();

        $this->assertModelMissing($bot);
    }
}
