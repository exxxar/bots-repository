<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductOptionStoreRequest;
use App\Http\Requests\ProductOptionUpdateRequest;
use App\Http\Resources\ProductOptionCollection;
use App\Http\Resources\ProductOptionResource;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductOptionController extends Controller
{
    public function index(Request $request): Response
    {
        $productOptions = ProductOption::all();

        return new ProductOptionCollection($productOptions);
    }

    public function store(ProductOptionStoreRequest $request): Response
    {
        $productOption = ProductOption::create($request->validated());

        return new ProductOptionResource($productOption);
    }

    public function show(Request $request, ProductOption $productOption): Response
    {
        return new ProductOptionResource($productOption);
    }

    public function update(ProductOptionUpdateRequest $request, ProductOption $productOption): Response
    {
        $productOption->update($request->validated());

        return new ProductOptionResource($productOption);
    }

    public function destroy(Request $request, ProductOption $productOption): Response
    {
        $productOption->delete();

        return response()->noContent();
    }
}
