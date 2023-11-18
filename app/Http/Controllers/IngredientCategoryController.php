<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientCategoryStoreRequest;
use App\Http\Requests\IngredientCategoryUpdateRequest;
use App\Http\Resources\IngredientCategoryCollection;
use App\Http\Resources\IngredientCategoryResource;
use App\Models\IngredientCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IngredientCategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $ingredientCategories = IngredientCategory::all();

        return new IngredientCategoryCollection($ingredientCategories);
    }

    public function store(IngredientCategoryStoreRequest $request): Response
    {
        $ingredientCategory = IngredientCategory::create($request->validated());

        return new IngredientCategoryResource($ingredientCategory);
    }

    public function show(Request $request, IngredientCategory $ingredientCategory): Response
    {
        return new IngredientCategoryResource($ingredientCategory);
    }

    public function update(IngredientCategoryUpdateRequest $request, IngredientCategory $ingredientCategory): Response
    {
        $ingredientCategory->update($request->validated());

        return new IngredientCategoryResource($ingredientCategory);
    }

    public function destroy(Request $request, IngredientCategory $ingredientCategory): Response
    {
        $ingredientCategory->delete();

        return response()->noContent();
    }
}
