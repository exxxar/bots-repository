<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotProductStoreRequest;
use App\Http\Requests\BotProductUpdateRequest;
use App\Http\Resources\BotProductCollection;
use App\Http\Resources\BotProductResource;
use App\Models\BotProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotProductController extends Controller
{
    public function index(Request $request)
    {
        $botProducts = BotProduct::all();

        return new BotProductCollection($botProducts);
    }

    public function store(BotProductStoreRequest $request): Response
    {
        $botProduct = BotProduct::create($request->validated());

        return new BotProductResource($botProduct);
    }

    public function show(Request $request, BotProduct $botProduct): Response
    {
        return new BotProductResource($botProduct);
    }

    public function update(BotProductUpdateRequest $request, BotProduct $botProduct): Response
    {
        $botProduct->update($request->validated());

        return new BotProductResource($botProduct);
    }

    public function destroy(Request $request, BotProduct $botProduct): Response
    {
        $botProduct->delete();

        return response()->noContent();
    }
}
