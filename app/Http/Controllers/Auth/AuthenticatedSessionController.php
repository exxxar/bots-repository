<?php

namespace App\Http\Controllers\Auth;

use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Service\Utilities;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    use Utilities;

    public function telegramAuth(Request $request)
    {

        $hash = $request->hash;
        $authDate = $request->auth_date;
        $authBotDomain = env("AUTH_BOT_DOMAIN");
        $tgId = $request->id;

        $bot = Bot::query()
            ->where("bot_domain", $authBotDomain)
            ->first();

        if (is_null($bot))
            return response()->redirectToRoute("login");

        if (!$this->checkTelegramAuthorization([
            "hash" => $hash,
            "bot_token" => $bot->bot_token,
            "auth_date" => $authDate
        ])) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Ошибка проверки данных входа");

            return response()->redirectToRoute("login");
        }



        Log::info("tgId=>$tgId");

        $user = User::query()
            ->where("email", "$tgId@your-cashman.ru")
            ->first();

        if (is_null($user)) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Пользователь еще не зарегистрировался в системе!");

            return response()->redirectToRoute("login");
        }


        $botUser = BotUser::query()
            ->where("bot_id",$bot->id)
            ->where("telegram_chat_id", $tgId)
            ->get();

        if (is_null($botUser)){
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Пользователь еще не зарегистрировался в системе!");
            return response()->redirectToRoute("login");
        }


        if (!$botUser->is_admin) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Пользователь не является сотрудником системы!");
            return response()->redirectToRoute("login");
        }


        if (Auth::attempt(['email' => $user->email, 'password' => $tgId])) {
            $request->session()->regenerate();

            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Отлично! Вы успешно авторизовались!");

            return redirect()->route('dashboard');
        }

        return response()->redirectToRoute("login");

    }

    /**
     * Display the login view.
     */
    public function create()
    {
        return view("admin.login");
        /*return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);*/
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
