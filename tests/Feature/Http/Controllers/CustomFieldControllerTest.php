<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BotCustomFieldSetting;
use App\Models\BotUser;
use App\Models\CustomField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CustomFieldController
 */
class CustomFieldControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $customFields = CustomField::factory()->count(3)->create();

        $response = $this->get(route('custom-field.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CustomFieldController::class,
            'store',
            \App\Http\Requests\CustomFieldStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot_user = BotUser::factory()->create();
        $bot_custom_field_setting = BotCustomFieldSetting::factory()->create();

        $response = $this->post(route('custom-field.store'), [
            'bot_user_id' => $bot_user->id,
            'bot_custom_field_setting_id' => $bot_custom_field_setting->id,
        ]);

        $customFields = CustomField::query()
            ->where('bot_user_id', $bot_user->id)
            ->where('bot_custom_field_setting_id', $bot_custom_field_setting->id)
            ->get();
        $this->assertCount(1, $customFields);
        $customField = $customFields->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $customField = CustomField::factory()->create();

        $response = $this->get(route('custom-field.show', $customField));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CustomFieldController::class,
            'update',
            \App\Http\Requests\CustomFieldUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $customField = CustomField::factory()->create();
        $bot_user = BotUser::factory()->create();
        $bot_custom_field_setting = BotCustomFieldSetting::factory()->create();

        $response = $this->put(route('custom-field.update', $customField), [
            'bot_user_id' => $bot_user->id,
            'bot_custom_field_setting_id' => $bot_custom_field_setting->id,
        ]);

        $customField->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot_user->id, $customField->bot_user_id);
        $this->assertEquals($bot_custom_field_setting->id, $customField->bot_custom_field_setting_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $customField = CustomField::factory()->create();

        $response = $this->delete(route('custom-field.destroy', $customField));

        $response->assertNoContent();

        $this->assertModelMissing($customField);
    }
}
