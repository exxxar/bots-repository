<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BasketStoreRequest;
use App\Http\Requests\BasketUpdateRequest;
use App\Http\Resources\BasketCollection;
use App\Http\Resources\BasketResource;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BasketController extends Controller
{
    public function index(Request $request): Response
    {
        $baskets = Basket::all();

        return new BasketCollection($baskets);
    }

    public function store(BasketStoreRequest $request): Response
    {
        $basket = Basket::create($request->validated());

        return new BasketResource($basket);
    }

    public function show(Request $request, Basket $basket): Response
    {
        return new BasketResource($basket);
    }

    public function update(BasketUpdateRequest $request, Basket $basket): Response
    {
        $basket->update($request->validated());

        return new BasketResource($basket);
    }

    public function destroy(Request $request, Basket $basket): Response
    {
        $basket->delete();

        return response()->noContent();
    }
}
