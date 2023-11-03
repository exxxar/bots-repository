<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashBackSubStoreRequest;
use App\Http\Requests\CashBackSubUpdateRequest;
use App\Http\Resources\CashBackSubCollection;
use App\Http\Resources\CashBackSubResource;
use App\Models\CashBackSub;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CashBackSubController extends Controller
{
    public function index(Request $request): Response
    {
        $cashBackSubs = CashBackSub::all();

        return new CashBackSubCollection($cashBackSubs);
    }

    public function store(CashBackSubStoreRequest $request): Response
    {
        $cashBackSub = CashBackSub::create($request->validated());

        return new CashBackSubResource($cashBackSub);
    }

    public function show(Request $request, CashBackSub $cashBackSub): Response
    {
        return new CashBackSubResource($cashBackSub);
    }

    public function update(CashBackSubUpdateRequest $request, CashBackSub $cashBackSub): Response
    {
        $cashBackSub->update($request->validated());

        return new CashBackSubResource($cashBackSub);
    }

    public function destroy(Request $request, CashBackSub $cashBackSub): Response
    {
        $cashBackSub->delete();

        return response()->noContent();
    }
}
