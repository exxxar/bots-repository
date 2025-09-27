<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BotMessages;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotMenuSlugCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MessageServiceController extends Controller
{
    public function index(Request $request)
    {
        $bot = Bot::query()
            ->find($request->botId ?? $request->bot_id ?? null);

        $mService = BotMessages::query($bot);

        return response()->json([
            "messages" => $mService->getMessages(),
            "dictionary" => $mService->getDictionary(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request)
    {

        $request->validate([
            "key" => "required",
            "value" => "required",
        ]);

        $bot = Bot::query()
            ->find($request->botId ?? $request->bot_id ?? null);


        BotMessages::query($bot)
            ->saveMessage($request->key, $request->value);

        return response()->noContent();
    }
}
