<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\BotCustomFieldSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotCustomFieldSettingController
 */
class BotCustomFieldSettingControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botCustomFieldSettings = BotCustomFieldSetting::factory()->count(3)->create();

        $response = $this->get(route('bot-custom-field-setting.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotCustomFieldSettingController::class,
            'store',
            \App\Http\Requests\BotCustomFieldSettingStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $required = $this->faker->boolean;

        $response = $this->post(route('bot-custom-field-setting.store'), [
            'bot_id' => $bot->id,
            'required' => $required,
        ]);

        $botCustomFieldSettings = BotCustomFieldSetting::query()
            ->where('bot_id', $bot->id)
            ->where('required', $required)
            ->get();
        $this->assertCount(1, $botCustomFieldSettings);
        $botCustomFieldSetting = $botCustomFieldSettings->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botCustomFieldSetting = BotCustomFieldSetting::factory()->create();

        $response = $this->get(route('bot-custom-field-setting.show', $botCustomFieldSetting));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotCustomFieldSettingController::class,
            'update',
            \App\Http\Requests\BotCustomFieldSettingUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botCustomFieldSetting = BotCustomFieldSetting::factory()->create();
        $bot = Bot::factory()->create();
        $required = $this->faker->boolean;

        $response = $this->put(route('bot-custom-field-setting.update', $botCustomFieldSetting), [
            'bot_id' => $bot->id,
            'required' => $required,
        ]);

        $botCustomFieldSetting->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $botCustomFieldSetting->bot_id);
        $this->assertEquals($required, $botCustomFieldSetting->required);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botCustomFieldSetting = BotCustomFieldSetting::factory()->create();

        $response = $this->delete(route('bot-custom-field-setting.destroy', $botCustomFieldSetting));

        $response->assertNoContent();

        $this->assertModelMissing($botCustomFieldSetting);
    }
}
