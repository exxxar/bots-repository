<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopStoreRequest;
use App\Http\Requests\ShopUpdateRequest;
use App\Http\Resources\ShopCollection;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShopController extends Controller
{
    public function index(Request $request): Response
    {
        $shops = Shop::all();

        return new ShopCollection($shops);
    }

    public function store(ShopStoreRequest $request): Response
    {
        $shop = Shop::create($request->validated());

        return new ShopResource($shop);
    }

    public function show(Request $request, Shop $shop): Response
    {
        return new ShopResource($shop);
    }

    public function update(ShopUpdateRequest $request, Shop $shop): Response
    {
        $shop->update($request->validated());

        return new ShopResource($shop);
    }

    public function destroy(Request $request, Shop $shop): Response
    {
        $shop->delete();

        return response()->noContent();
    }
}
