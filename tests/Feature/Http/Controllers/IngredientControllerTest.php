<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bot;
use App\Models\FoodConstructor;
use App\Models\Ingredient;
use App\Models\IngredientCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\IngredientController
 */
class IngredientControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $ingredients = Ingredient::factory()->count(3)->create();

        $response = $this->get(route('ingredient.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\IngredientController::class,
            'store',
            \App\Http\Requests\IngredientStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $bot = Bot::factory()->create();
        $food_constructor = FoodConstructor::factory()->create();
        $ingredient_category = IngredientCategory::factory()->create();
        $is_checked = $this->faker->boolean;
        $is_disabled = $this->faker->boolean;
        $is_global = $this->faker->boolean;

        $response = $this->post(route('ingredient.store'), [
            'bot_id' => $bot->id,
            'food_constructor_id' => $food_constructor->id,
            'ingredient_category_id' => $ingredient_category->id,
            'is_checked' => $is_checked,
            'is_disabled' => $is_disabled,
            'is_global' => $is_global,
        ]);

        $ingredients = Ingredient::query()
            ->where('bot_id', $bot->id)
            ->where('food_constructor_id', $food_constructor->id)
            ->where('ingredient_category_id', $ingredient_category->id)
            ->where('is_checked', $is_checked)
            ->where('is_disabled', $is_disabled)
            ->where('is_global', $is_global)
            ->get();
        $this->assertCount(1, $ingredients);
        $ingredient = $ingredients->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $ingredient = Ingredient::factory()->create();

        $response = $this->get(route('ingredient.show', $ingredient));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\IngredientController::class,
            'update',
            \App\Http\Requests\IngredientUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $ingredient = Ingredient::factory()->create();
        $bot = Bot::factory()->create();
        $food_constructor = FoodConstructor::factory()->create();
        $ingredient_category = IngredientCategory::factory()->create();
        $is_checked = $this->faker->boolean;
        $is_disabled = $this->faker->boolean;
        $is_global = $this->faker->boolean;

        $response = $this->put(route('ingredient.update', $ingredient), [
            'bot_id' => $bot->id,
            'food_constructor_id' => $food_constructor->id,
            'ingredient_category_id' => $ingredient_category->id,
            'is_checked' => $is_checked,
            'is_disabled' => $is_disabled,
            'is_global' => $is_global,
        ]);

        $ingredient->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($bot->id, $ingredient->bot_id);
        $this->assertEquals($food_constructor->id, $ingredient->food_constructor_id);
        $this->assertEquals($ingredient_category->id, $ingredient->ingredient_category_id);
        $this->assertEquals($is_checked, $ingredient->is_checked);
        $this->assertEquals($is_disabled, $ingredient->is_disabled);
        $this->assertEquals($is_global, $ingredient->is_global);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $ingredient = Ingredient::factory()->create();

        $response = $this->delete(route('ingredient.destroy', $ingredient));

        $response->assertNoContent();

        $this->assertModelMissing($ingredient);
    }
}
