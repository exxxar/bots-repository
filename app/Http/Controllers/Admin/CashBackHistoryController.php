<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashBackHistoryStoreRequest;
use App\Http\Requests\CashBackHistoryUpdateRequest;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\CashBackHistoryResource;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBackHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CashBackHistoryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
           "user_telegram_chat_id"=>"required"
        ]);

        $bot = $request->bot ?? null;
        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $size = $request->get("size") ?? config('app.results_per_page');

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $bot->id)
            ->where("user_id", $botUser->user_id)
            ->orderBy("created_at", "desc")
            ->paginate($size);

        return CashBackHistoryResource::collection($cashBackHistories);
    }

    public function receiver(Request $request)
    {
        $request->validate([
            "user_telegram_chat_id"=>"required"
        ]);

        $bot = $request->bot ?? null;
        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        return new BotUserResource($botUser);
    }
}
