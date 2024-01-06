<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Service\Utilities;
use App\Models\Bot;
use App\Models\BotUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    use Utilities;


    public function handle(Request $request, Closure $next, $role): Response
    {
        $botUser = Session::get("bot_user") ?? null;

        $botUser = BotUser::query()
            ->find($botUser->id);

        if (is_null($botUser)) {
            return \response()->json(["error" => "BotUser not found"], 404);
        }

        Session::remove("bot_user");
        Session::put("bot_user", $botUser);

        if (!is_null($botUser->deleted_at ?? null)||!is_null($botUser->blocked_at ?? null))
            return \response()->json(["error" => "BotUser is blocked user"], 404);


        if (auth()->check() && $botUser->is_admin && ($role == "admin" || $role == "manager")) {
            $request->botUser = $botUser;
            return $next($request);
        }

        if (auth()->check() && $botUser->is_manager && $role == "manager") {
            $request->botUser = $botUser;
            return $next($request);
        }


        return \response()->json(["error" => "No permissions"], 403);
    }
}
