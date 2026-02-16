<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Bot;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckBotDomain
{
    public function handle($request, Closure $next)
    {
        // Валидация входных данных
        $request->validate([
            "bot_domain" => "required"
        ]);

        // Поиск бота
        $bot = Bot::query()
            ->where("bot_domain", $request->bot_domain)
            ->first();

        if (is_null($bot)) {
            throw new HttpException(404, "Магазин не найден");
        }

        // Прокидываем бота в запрос
        $request->merge([
            "bot" => $bot
        ]);

        return $next($request);
    }
}
