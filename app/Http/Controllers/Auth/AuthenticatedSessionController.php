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
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    use Utilities;

    public function telegramAuth(Request $request, $domain)
    {

        $authBotDomain = $domain ?? env("AUTH_BOT_DOMAIN");
        $tgId = $request->get("id");

        $bot = Bot::query()
            ->where("bot_domain", $authBotDomain)
            ->first();

        if (is_null($bot))
            return response()->redirectToRoute("login");

        if (!$this->checkTelegramAuthorization($request->all(), $bot->bot_token)) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Ошибка проверки данных входа");

            return response()->redirectToRoute("login");
        }
        /*
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
                }*/


        $botUser = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $tgId)
            ->first();

        if (is_null($botUser)) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Пользователь еще не зарегистрировался в системе!");
            return response()->redirectToRoute("login");
        }


        if (!$botUser->is_admin&&!$botUser->is_manager) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Вы не являетесь сотрудником системы!");
            return response()->redirectToRoute("login");
        }


        $user = $botUser->user;

        /*Log::info(print_r($user->toArray(), true));
        if (Auth::attempt(['email' => $user->email, 'password' => $tgId])) {*/

        Auth::login($user);

        Session::put("bot_user", $botUser);

        $request->session()->regenerate();

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $tgId,
                "Отлично! Вы успешно авторизовались!");

        return redirect()->route('dashboard');
        /* }

         BotMethods::bot()
             ->whereBot($bot)
             ->sendMessage(
                 $tgId,
                 "Ошибка авторизации");

         return response()->redirectToRoute("login");*/

    }

    /**
     * Display the login view.
     */
    public function create()
    {
        $debug = env("APP_DEBUG");
        if ($debug) {

            $botUser = BotUser::query()
                ->where("is_admin", true)
                ->first();

            Session::put("bot_user", $botUser);


            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        } else
            return view("admin.login");
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        if (env("app_debug")) {
            $botUser = BotUser::query()
                ->where("is_admin", true)
                ->first();

            Session::put("bot_user", $botUser);
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        Session::remove("bot_user");

        $request->session()->invalidate();


        $request->session()->regenerateToken();

        return redirect('/');
    }
}
