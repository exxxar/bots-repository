<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodConstructorStoreRequest;
use App\Http\Requests\FoodConstructorUpdateRequest;
use App\Http\Resources\FoodConstructorCollection;
use App\Http\Resources\FoodConstructorResource;
use App\Models\FoodConstructor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FoodConstructorController extends Controller
{
    public function index(Request $request): Response
    {
        $foodConstructors = FoodConstructor::all();

        return new FoodConstructorCollection($foodConstructors);
    }

    public function store(FoodConstructorStoreRequest $request): Response
    {
        $foodConstructor = FoodConstructor::create($request->validated());

        return new FoodConstructorResource($foodConstructor);
    }

    public function show(Request $request, FoodConstructor $foodConstructor): Response
    {
        return new FoodConstructorResource($foodConstructor);
    }

    public function update(FoodConstructorUpdateRequest $request, FoodConstructor $foodConstructor): Response
    {
        $foodConstructor->update($request->validated());

        return new FoodConstructorResource($foodConstructor);
    }

    public function destroy(Request $request, FoodConstructor $foodConstructor): Response
    {
        $foodConstructor->delete();

        return response()->noContent();
    }
}
