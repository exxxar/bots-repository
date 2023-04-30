<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashBackHistoryStoreRequest;
use App\Http\Requests\CashBackHistoryUpdateRequest;
use App\Http\Resources\CashBackHistoryResource;
use App\Models\CashBackHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CashBackHistoryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "user_id" => "required",
            "bot_id" => "required",
        ]);

        $size = $request->get("size") ?? config('app.results_per_page');

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $request->bot_id)
            ->where("user_id", $request->user_id)
            ->paginate($size);

        return CashBackHistoryResource::collection($cashBackHistories);
    }



    public function store(CashBackHistoryStoreRequest $request): Response
    {
        $cashBackHistory = CashBackHistory::create($request->validated());

        return new CashBackHistoryResource($cashBackHistory);
    }

    public function show(Request $request, CashBackHistory $cashBackHistory): Response
    {
        return new CashBackHistoryResource($cashBackHistory);
    }

    public function update(CashBackHistoryUpdateRequest $request, CashBackHistory $cashBackHistory): Response
    {
        $cashBackHistory->update($request->validated());

        return new CashBackHistoryResource($cashBackHistory);
    }

    public function destroy(Request $request, CashBackHistory $cashBackHistory): Response
    {
        $cashBackHistory->delete();

        return response()->noContent();
    }
}
