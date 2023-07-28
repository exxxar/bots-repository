<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Service\Utilities;
use App\Models\Bot;
use App\Models\BotUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TelegramAuthCheck
{
    use Utilities;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $botDomain = $request->botDomain ?? null;

        if (is_null($botDomain)) {
            Log::info("bot domain not found");
            return \response()->json(["error" => "bot domain not found"], 400);
        }

        $bot = Bot::query()->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            return \response()->json(["error" => "bot not found"], 404);
        }

        parse_str($request->tgData, $arr);

        $tgUser = $arr['user'] ?? null;

        if (is_null($tgUser))
            return \response()->json(["error" => "TG user not found"], 404);

        $tgUser = json_decode($tgUser);
        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $tgUser->id)
            ->first();

        if (is_null($botUser))
            return \response()->json(["error" => "Bot User not found"], 404);

        if ($this->validateTGData($bot->bot_token, $request->tgData)) {

            $request->botUser = $botUser;
            $request->bot = $bot;

            return $next($request);
        } else {
            return \response()->json(["error" => "some error"], 400);
        }

    }
}
