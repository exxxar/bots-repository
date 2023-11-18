<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\FoodConstructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FoodConstructorController
 */
class FoodConstructorControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $foodConstructors = FoodConstructor::factory()->count(3)->create();

        $response = $this->get(route('food-constructor.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FoodConstructorController::class,
            'store',
            \App\Http\Requests\FoodConstructorStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();

        $response = $this->post(route('food-constructor.store'), [
            'bot_id' => $bot->id,
        ]);

        $foodConstructors = FoodConstructor::query()
            ->where('bot_id', $bot->id)
            ->get();
        $this->assertCount(1, $foodConstructors);
        $foodConstructor = $foodConstructors->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $foodConstructor = FoodConstructor::factory()->create();

        $response = $this->get(route('food-constructor.show', $foodConstructor));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\FoodConstructorController::class,
            'update',
            \App\Http\Requests\FoodConstructorUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $foodConstructor = FoodConstructor::factory()->create();
        $bot = Bot::factory()->create();

        $response = $this->put(route('food-constructor.update', $foodConstructor), [
            'bot_id' => $bot->id,
        ]);

        $foodConstructor->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $foodConstructor->bot_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $foodConstructor = FoodConstructor::factory()->create();

        $response = $this->delete(route('food-constructor.destroy', $foodConstructor));

        $response->assertNoContent();

        $this->assertModelMissing($foodConstructor);
    }
}
