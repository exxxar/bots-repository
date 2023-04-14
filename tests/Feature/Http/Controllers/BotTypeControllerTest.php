<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BotType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BotTypeController
 */
class BotTypeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $botTypes = BotType::factory()->count(3)->create();

        $response = $this->get(route('bot-type.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotTypeController::class,
            'store',
            \App\Http\Requests\BotTypeStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;

        $response = $this->post(route('bot-type.store'), [
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $botTypes = BotType::query()
            ->where('slug', $slug)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $botTypes);
        $botType = $botTypes->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $botType = BotType::factory()->create();

        $response = $this->get(route('bot-type.show', $botType));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BotTypeController::class,
            'update',
            \App\Http\Requests\BotTypeUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $botType = BotType::factory()->create();
        $slug = $this->faker->slug;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('bot-type.update', $botType), [
            'slug' => $slug,
            'is_active' => $is_active,
        ]);

        $botType->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($slug, $botType->slug);
        $this->assertEquals($is_active, $botType->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $botType = BotType::factory()->create();

        $response = $this->delete(route('bot-type.destroy', $botType));

        $response->assertNoContent();

        $this->assertModelMissing($botType);
    }
}
