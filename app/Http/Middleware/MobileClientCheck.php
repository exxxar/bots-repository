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

class MobileClientCheck
{
    use Utilities;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headerBotDomainEncrypted = $request->header("x-cashman-mobile-bot-domain") ?? null;

        $botDomain = $request->botDomain ?? $request->bot_domain ?? null;

        if (!is_null($headerBotDomainEncrypted))
            $botDomain = base64_decode($headerBotDomainEncrypted);

        if (is_null($botDomain)) {
            return \response()->json(["error" => "bot domain not found"], 400);
        }

        $bot = Bot::query()
            ->with(["company"])
            ->where("bot_domain", $botDomain)
            ->first();


        if (is_null($bot)) {
            return \response()->json(["error" => "bot not found"], 404);
        }

        $request->bot = $bot;

        return $next($request);

    }
}
