<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientStoreRequest;
use App\Http\Requests\IngredientUpdateRequest;
use App\Http\Resources\IngredientCollection;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IngredientController extends Controller
{
    public function index(Request $request): Response
    {
        $ingredients = Ingredient::all();

        return new IngredientCollection($ingredients);
    }

    public function store(IngredientStoreRequest $request): Response
    {
        $ingredient = Ingredient::create($request->validated());

        return new IngredientResource($ingredient);
    }

    public function show(Request $request, Ingredient $ingredient): Response
    {
        return new IngredientResource($ingredient);
    }

    public function update(IngredientUpdateRequest $request, Ingredient $ingredient): Response
    {
        $ingredient->update($request->validated());

        return new IngredientResource($ingredient);
    }

    public function destroy(Request $request, Ingredient $ingredient): Response
    {
        $ingredient->delete();

        return response()->noContent();
    }
}
