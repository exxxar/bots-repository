<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Service\Utilities;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\User;
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
        $headerTgDataEncrypted = $request->header("X-Cashman-Tg-Data") ?? null;
        $headerBotDomainEncrypted = $request->header("X-Cashman-Bot-Domain") ?? null;

        $botDomain = $request->botDomain ?? $request->bot_domain ?? null;

        if (!is_null($headerBotDomainEncrypted))
            $botDomain = base64_decode($headerBotDomainEncrypted);

        $isDebug = env("APP_DEBUG");
        $debugBotUser = env("DEBUG_BOT_USER") ?? null;

        if ($isDebug){
            $bot = Bot::query()
                ->withTrashed()
                ->with(["company"])
                ->where("bot_domain", $botDomain)
                ->first();

            $botUser = BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("telegram_chat_id", $debugBotUser)
                ->first();

            if (is_null($botUser)) {
                $user = User::query()->
                    where("email","admin@admin.com")
                    ->first();

                $botUser = BotUser::query()
                    ->create([
                        'user_id'=>$user->id,
                        'bot_id'=>$bot->id,
                        'telegram_chat_id'=>$debugBotUser,
                        'is_vip'=>true,
                        'is_admin'=>true,
                        'is_work'=>true,
                        'is_manager'=>true,
                        'is_deliveryman'=>true,
                        'name'=>"Test",
                        'username'=>"testuser",
                        'phone'=>"+79490000000",
                        'email'=>"testuser@gmail.com",
                        'fio_from_telegram'=>"testtesttest",
                    ]);
            }


            $request->botUser = $botUser;
            $request->bot = $bot;

            return $next($request);
        }

        if (is_null($botDomain)) {
           // Log::info("bot domain not found");
            return \response()->json(["error" => "bot domain not found"], 400);
        }

        $bot = Bot::query()
            ->with(["company"])
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            return \response()->json(["error" => "bot not found"], 404);
        }

        $tgData = $request->tgData ?? null;

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
            return \response()->json(["error" => "Bot User not found"], 404);

        if ($this->validateTGData($bot->bot_token, $tgData)) {

            $request->botUser = $botUser;
            $request->bot = $bot;

            return $next($request);
        } else {
            return \response()->json(["error" => "some error"], 400);
        }

    }
}
