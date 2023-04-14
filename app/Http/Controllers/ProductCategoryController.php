<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryStoreRequest;
use App\Http\Requests\ProductCategoryUpdateRequest;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $productCategories = ProductCategory::all();

        return new ProductCategoryCollection($productCategories);
    }

    public function store(ProductCategoryStoreRequest $request): Response
    {
        $productCategory = ProductCategory::create($request->validated());

        return new ProductCategoryResource($productCategory);
    }

    public function show(Request $request, ProductCategory $productCategory): Response
    {
        return new ProductCategoryResource($productCategory);
    }

    public function update(ProductCategoryUpdateRequest $request, ProductCategory $productCategory): Response
    {
        $productCategory->update($request->validated());

        return new ProductCategoryResource($productCategory);
    }

    public function destroy(Request $request, ProductCategory $productCategory): Response
    {
        $productCategory->delete();

        return response()->noContent();
    }
}
