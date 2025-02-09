<?php

namespace App\Http\Controllers\Auth;

use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Service\Utilities;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\BotSecurityResource;
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


        if (!$botUser->is_admin && !$botUser->is_manager) {

            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Вы стали менеджером! Работаем дальше:)");

            $botUser->is_manager = true;
            $botUser->save();
        }

        $user = $botUser->user;

        /*Log::info(print_r($user->toArray(), true));
        if (Auth::attempt(['email' => $user->email, 'password' => $tgId])) {*/

        Auth::login($user);

        Session::put("bot_user", $botUser);
        Session::put("bot", (new BotSecurityResource($bot))->toJson());

        $request->session()->regenerate();

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $tgId,
                "Отлично! Вы успешно авторизовались!");

        return redirect()->route('dashboard');


    }

    public function telegramLinkAuth(Request $request)
    {
        $request->validate([
            "id" => "required",
            "hash" => "required",
            "auth_date" => "required",
        ]);

        Auth::logout();

        Session::remove("bot_user");

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $tgId = $request->get("id");

        $bot = Bot::query()
            ->where("bot_domain", env("AUTH_BOT_DOMAIN"))
            ->first();

        if (is_null($bot))
            return response()->redirectTo("login");

        if (!$this->checkTelegramAuthorization($request->all(), $bot->bot_token)) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Ошибка проверки данных входа");

            return response()->redirectToRoute("login");
        }

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


        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $tgId,
                    "Вы стали менеджером! Работаем дальше:)");

            $botUser->is_manager = true;
            $botUser->save();
            // return response()->redirectToRoute("login");
        }


        $user = $botUser->user;

        Auth::login($user);

        Session::put("bot_user", $botUser);
        Session::put("bot", (new BotSecurityResource($bot))->toJson());

        $request->session()->regenerate();

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $tgId,
                "Отлично! Вы успешно авторизовались!");

        return redirect()->route('dashboard');


    }

    public function magicLogin(Request $request)
    {
        Log::info("user=>".print_r($request->user, true));
        Log::info("hasValidSignature ".($request->hasValidSignature()?"true":"false"));
        Log::info("expire ".(now()->timestamp > $request->expire_at ? "true":"false")."| now ".now()->timestamp." expire=".$request->expire_at);
        // Проверяем, что ссылка подписана верно
        if (!$request->hasValidSignature() || now()->timestamp > $request->expire_at) {
            abort(403, 'Ссылка недействительна или истекла');
        }

        // Авторизуем пользователя
        $user = User::findOrFail($request->user);
        Auth::login($user);

        $authBotDomain = $domain ?? env("AUTH_BOT_DOMAIN");

        $bot = Bot::query()
            ->where("bot_domain", $authBotDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("user_id", Auth::user()->id)
            ->first();

        if (is_null($botUser)) {
            Auth::guard('web')->logout();

            Session::remove("bot_user");
            Session::remove("bot");

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route("login");
        }

        $request->session()->regenerate();


        $request->session()->put("bot_user", $botUser);
        $request->session()->put("bot", (new BotSecurityResource($bot))->toJson());

        return redirect('/dev')
            ->with([
                "bot_user", $botUser,
                "bot", $bot
            ]);
    }


    /**
     * Display the login view.
     */
    public function create(Request $request)
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);

    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($credentials)) {
            Log::info("Неверный логин или пароль для входа");
            return back()->withErrors(['email' => 'Неверный email или пароль'])->withInput();
        }

        $authBotDomain = env("AUTH_BOT_DOMAIN");

        Log::info("домен для бота входа $authBotDomain");

        $bot = Bot::query()
            ->where("bot_domain", $authBotDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("user_id", Auth::user()->id)
            ->first();

        $debug = env("APP_DEBUG");
        if ($debug) {
            $botUser = $botUser ?? BotUser::query()
                ->where("is_admin", true)
                ->first();

            $request->session()->regenerate();

            $authBotDomain = $domain ?? env("AUTH_BOT_DOMAIN");

            $bot = Bot::query()
                ->where("bot_domain", $authBotDomain)
                ->first();

            $request->session()->put("bot_user", $botUser);
            $request->session()->put("bot", (new BotSecurityResource($bot))->toJson());

            return redirect('/dev');
        }

        if (is_null($botUser)) {

            $botDomain = env("AUTH_BOT_DOMAIN");
            $link = "https://t.me/$botDomain?start=" . base64_encode("777register" . Auth::user()->id);

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Inertia::render('Auth/RegisterStep2',[
                "link"=>$link
            ]);
            /*  Auth::guard('web')->logout();

              Session::remove("bot_user");
              Session::remove("bot");

              $request->session()->invalidate();
              $request->session()->regenerateToken();

              return redirect()->back()
                  ->withErrors(['system' => 'Авторизация отклонена']) // Ошибка для конкретного поля
                  ->withInput(); // Возвращает введенные данные (кроме пароля)*/
        }


        $request->session()->regenerate();

        $request->session()->put("bot_user", $botUser);
        $request->session()->put("bot", (new BotSecurityResource($bot))->toJson());

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with([
                "bot_user", $botUser,
                "bot", $bot
            ]);


    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        Session::remove("bot_user");
        Session::remove("bot");

        $request->session()->invalidate();


        $request->session()->regenerateToken();

        return redirect('/');
    }
}
