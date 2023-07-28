<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashBackHistoryStoreRequest;
use App\Http\Requests\CashBackHistoryUpdateRequest;
use App\Http\Resources\CashBackHistoryResource;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBackHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CashBackHistoryController extends Controller
{
    public function index(Request $request, $botDomain)
    {
        $request->validate([
            "telegram_chat_id" => "required",

        ]);

        $bot = Bot::query()->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot))
            return response()->noContent(404);

        $size = $request->get("size") ?? config('app.results_per_page');

        $botUser = BotUser::query()
            ->where("telegram_chat_id", $request->telegram_chat_id)
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $botUser->bot_id)
            ->where("user_id", $botUser->user_id)
            ->orderBy("created_at", "desc")
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
