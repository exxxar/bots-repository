<?php

namespace App\Http\Controllers\Bots\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatLogStoreRequest;
use App\Http\Requests\ChatLogUpdateRequest;
use App\Http\Resources\ChatLogCollection;
use App\Http\Resources\ChatLogResource;
use App\Models\ChatLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChatLogController extends Controller
{
    public function history(Request $request): ChatLogCollection
    {
        $request->validate([
            "bot_user_id" => "required"
        ]);

        $bot = $request->bot ?? null;

        $botUserId = $request->bot_user_id ?? null;

        $chatLogs = ChatLog::query()
            ->where("bot_id", $bot->id)
            ->where(function ($q) use ($botUserId) {
                $q->where("form_bot_user_id", $botUserId)
                    ->orWhere("to_bot_user_id", $botUserId);
            })
            ->orderBy("created_at","desc")
            ->paginate(20);

        return new ChatLogCollection($chatLogs);
    }

}
