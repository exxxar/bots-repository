<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\IngredientCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\IngredientCategoryController
 */
class IngredientCategoryControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $ingredientCategories = IngredientCategory::factory()->count(3)->create();

        $response = $this->get(route('ingredient-category.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\IngredientCategoryController::class,
            'store',
            \App\Http\Requests\IngredientCategoryStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $response = $this->post(route('ingredient-category.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(ingredientCategories, [ /* ... */ ]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $ingredientCategory = IngredientCategory::factory()->create();

        $response = $this->get(route('ingredient-category.show', $ingredientCategory));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\IngredientCategoryController::class,
            'update',
            \App\Http\Requests\IngredientCategoryUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $ingredientCategory = IngredientCategory::factory()->create();

        $response = $this->put(route('ingredient-category.update', $ingredientCategory));

        $ingredientCategory->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $ingredientCategory = IngredientCategory::factory()->create();

        $response = $this->delete(route('ingredient-category.destroy', $ingredientCategory));

        $response->assertNoContent();

        $this->assertModelMissing($ingredientCategory);
    }
}
