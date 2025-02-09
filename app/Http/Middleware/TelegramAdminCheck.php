<?php

namespace App\Http\Middleware;

use App\Facades\BotMethods;
use App\Http\Middleware\Service\Utilities;
use App\Models\Bot;
use App\Models\BotUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TelegramAdminCheck
{
    use Utilities;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $headerTgDataEncrypted = $request->header("X-Cashman-Tg-Data") ?? null;
        $headerBotDomainEncrypted = $request->header("X-Cashman-Bot-Domain") ?? null;

        $botDomain = $request->botDomain ?? $request->bot_domain ?? null;

        if (!is_null($headerBotDomainEncrypted))
            $botDomain = base64_decode($headerBotDomainEncrypted);

        $isDebug = env("APP_DEBUG");
        $debugBotUser = env("DEBUG_BOT_USER") ?? null;

        if ($isDebug) {
            $bot = Bot::query()
                ->withTrashed()
                ->with(["company","amo"])
                ->where("bot_domain", $botDomain)
                ->first();

            $botUser = BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("telegram_chat_id", $debugBotUser)
                ->first();

            $request->botUser = $botUser;
            $request->bot = $bot;

            return $next($request);
        }

        if (is_null($botDomain))
            return \response()->json(["error" => "bot domain not found"], 400);


        $bot = Bot::query()
            ->with(["company"])
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot))
            return \response()->json(["error" => "bot not found"], 400);

        $tgData = $request->tgData;
        if (!is_null($headerTgDataEncrypted))
            $tgData = base64_decode($headerTgDataEncrypted);

        parse_str($tgData, $arr);

        $tgUser = $arr['user'] ?? null;

        if (is_null($tgUser))
            return \response()->json(["error" => "TG user not found"], 404);

        $tgUser = json_decode($tgUser);

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $tgUser->id)
            ->first();

        if (is_null($botUser))
            return \response()->json(["error" => "Bot User not found"], 400);

        if (!$botUser->is_admin) {
          /*  BotMethods::bot()
                ->whereId($bot->id)
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Вы не являетесь администратором данного бота! Данное действие недоступно!"
                );*/
            return \response()->json(["error" => "User is not admin"], 400);
        }


        if ($this->validateTGData($bot->bot_token, $tgData)) {
            $request->botUser = $botUser;
            $request->bot = $bot;

            return $next($request);
        } else {
            return \response()->json(["error" => "some error"], 400);
        }


    }
}
