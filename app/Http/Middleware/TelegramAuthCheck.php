<?php

namespace App\Http\Middleware;

use App\Models\Bot;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TelegramAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $botDomain = $request->route('botDomain') ?? null;

        if (is_null($botDomain))
        {
            Log::info("bot domain not found");
            return \response()->json(["error"=>"bot domain not found"]);
        }

        $bot = Bot::query()->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot))
        {
            Log::info("bot not found");
            return \response()->json(["error"=>"bot not found"]);
        }

        $bot_secret = $bot->bot_token;

        $in =  $request->tgData;

        parse_str($in, $arr);

        $check_hash = $arr['hash'];
        unset($arr['hash']);
        ksort($arr);
        $data_str = "";
        foreach($arr as $k=>$v) {
            $data_str .= $k."=".$v."\x0A";
        }
        $data_str = trim($data_str);

        $secret = hash_hmac('sha256', $bot_secret, 'WebAppData', true);
        $hash = hash_hmac('sha256', $data_str, $secret);

        if( strcmp($hash, $check_hash) === 0 ){
            Log::info("CHECK SUCCESS");
            return $next($request);
        }else{
            return \response()->json(["error"=>"some error"]);
        }

    }
}
