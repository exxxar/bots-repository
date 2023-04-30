<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotProductCategoryStoreRequest;
use App\Http\Requests\BotProductCategoryUpdateRequest;
use App\Http\Resources\BotProductCategoryCollection;
use App\Http\Resources\BotProductCategoryResource;
use App\Models\BotProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotProductCategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $botProductCategories = BotProductCategory::all();

        return new BotProductCategoryCollection($botProductCategories);
    }

    public function store(BotProductCategoryStoreRequest $request): Response
    {
        $botProductCategory = BotProductCategory::create($request->validated());

        return new BotProductCategoryResource($botProductCategory);
    }

    public function show(Request $request, BotProductCategory $botProductCategory): Response
    {
        return new BotProductCategoryResource($botProductCategory);
    }

    public function update(BotProductCategoryUpdateRequest $request, BotProductCategory $botProductCategory): Response
    {
        $botProductCategory->update($request->validated());

        return new BotProductCategoryResource($botProductCategory);
    }

    public function destroy(Request $request, BotProductCategory $botProductCategory): Response
    {
        $botProductCategory->delete();

        return response()->noContent();
    }
}
