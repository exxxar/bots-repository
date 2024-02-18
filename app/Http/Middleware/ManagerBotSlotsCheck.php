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
use Symfony\Component\HttpKernel\Exception\HttpException;

class ManagerBotSlotsCheck
{
    use Utilities;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $botUser = $request->botUser ?? null;

        if (is_null($botUser)) {
            return \response()->json(["error" => "Не найден пользователь бота"], 404);
        }

        $manager = $botUser->manager ?? null;

        if (is_null($manager)) {
            return \response()->json(["error" => "Пользователь не имеет профиля менеджера"], 404);
        }

        /* if ($manager->max_bot_slot_count==0)
             return \response()->json(["error" => "Недостаточное количество слотов для выполнения операции"], 400);*/

        $response = $next($request);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $manager->max_bot_slot_count--;
            $manager->save();

            $botUser = $botUser->refresh();

            Session::put("bot_user", $botUser);
        }

        return $response;

    }

}
