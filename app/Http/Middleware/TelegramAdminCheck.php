<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Service\Utilities;
use App\Models\Bot;
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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $botDomain = $request->route('botDomain') ?? null;

        if (is_null($botDomain)) {
            Log::info("bot domain not found");
            return \response()->json(["error" => "bot domain not found"], 400);
        }

        $bot = Bot::query()->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Log::info("bot not found");
            return \response()->json(["error" => "bot not found"], 400);
        }

        parse_str($request->tgData, $arr);

        $user= $arr['user'];

        Log::info("full=>".print_r($request->tgData, true));
        Log::info("USER=>".print_r(json_decode($user), true));

        if ($this->validateTGData($bot->bot_token, $request->tgData)) {
            return $next($request);
        } else {
            return \response()->json(["error" => "some error"], 400);
        }


    }
}
