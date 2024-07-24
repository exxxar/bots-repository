<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubShopStoreRequest;
use App\Http\Requests\SubShopUpdateRequest;
use App\Http\Resources\SubShopCollection;
use App\Http\Resources\SubShopResource;
use App\Models\SubShop;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubShopController extends Controller
{
    public function index(Request $request): Response
    {
        $subShops = SubShop::all();

        return new SubShopCollection($subShops);
    }

    public function store(SubShopStoreRequest $request): Response
    {
        $subShop = SubShop::create($request->validated());

        return new SubShopResource($subShop);
    }

    public function show(Request $request, SubShop $subShop): Response
    {
        return new SubShopResource($subShop);
    }

    public function update(SubShopUpdateRequest $request, SubShop $subShop): Response
    {
        $subShop->update($request->validated());

        return new SubShopResource($subShop);
    }

    public function destroy(Request $request, SubShop $subShop): Response
    {
        $subShop->delete();

        return response()->noContent();
    }
}
