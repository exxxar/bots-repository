<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotWarning;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotWarningController
 */
class BotWarningControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botWarnings = BotWarning::factory()->count(3)->create();

        $response = $this->get(route('bot-warning.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotWarningController::class,
            'store',
            \App\Http\Requests\BotWarningStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $rule_key = $this->faker->word;
        $rule_value = $this->faker->numberBetween(-10000, 10000);
        $is_active = $this->faker->boolean;

        $response = $this->post(route('bot-warning.store'), [
            'bot_id' => $bot->id,
            'rule_key' => $rule_key,
            'rule_value' => $rule_value,
            'is_active' => $is_active,
        ]);

        $botWarnings = BotWarning::query()
            ->where('bot_id', $bot->id)
            ->where('rule_key', $rule_key)
            ->where('rule_value', $rule_value)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $botWarnings);
        $botWarning = $botWarnings->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botWarning = BotWarning::factory()->create();

        $response = $this->get(route('bot-warning.show', $botWarning));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotWarningController::class,
            'update',
            \App\Http\Requests\BotWarningUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botWarning = BotWarning::factory()->create();
        $bot = Bot::factory()->create();
        $rule_key = $this->faker->word;
        $rule_value = $this->faker->numberBetween(-10000, 10000);
        $is_active = $this->faker->boolean;

        $response = $this->put(route('bot-warning.update', $botWarning), [
            'bot_id' => $bot->id,
            'rule_key' => $rule_key,
            'rule_value' => $rule_value,
            'is_active' => $is_active,
        ]);

        $botWarning->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $botWarning->bot_id);
        $this->assertEquals($rule_key, $botWarning->rule_key);
        $this->assertEquals($rule_value, $botWarning->rule_value);
        $this->assertEquals($is_active, $botWarning->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botWarning = BotWarning::factory()->create();

        $response = $this->delete(route('bot-warning.destroy', $botWarning));

        $response->assertNoContent();

        $this->assertModelMissing($botWarning);
    }
}
