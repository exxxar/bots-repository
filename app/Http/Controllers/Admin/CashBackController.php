<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashBackStoreRequest;
use App\Http\Requests\CashBackUpdateRequest;
use App\Http\Resources\CashBackCollection;
use App\Http\Resources\CashBackResource;
use App\Models\CashBack;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CashBackController extends Controller
{
    public function index(Request $request): Response
    {
        $cashBacks = CashBack::all();

        return new CashBackCollection($cashBacks);
    }

    public function store(CashBackStoreRequest $request): Response
    {
        $cashBack = CashBack::create($request->validated());

        return new CashBackResource($cashBack);
    }

    public function show(Request $request, CashBack $cashBack): Response
    {
        return new CashBackResource($cashBack);
    }

    public function update(CashBackUpdateRequest $request, CashBack $cashBack): Response
    {
        $cashBack->update($request->validated());

        return new CashBackResource($cashBack);
    }

    public function destroy(Request $request, CashBack $cashBack): Response
    {
        $cashBack->delete();

        return response()->noContent();
    }
}
