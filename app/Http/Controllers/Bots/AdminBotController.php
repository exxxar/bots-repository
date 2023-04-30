<?php

namespace App\Http\Controllers\Bots;

use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotUserResource;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminBotController extends Controller
{
    public function adminMenu($botDomain, $userId)
    {

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $userId)
            ->first();

        $cashBack = \App\Models\CashBack::query()
            ->where("bot_id", $bot->id)
            ->where("user_id", $botUser->user_id)
            ->first();

        $user = new \App\Http\Resources\UserResource($botUser->user);
        $botUser = new \App\Http\Resources\BotUserResource($botUser);
        $cashBack = new \App\Http\Resources\CashBackResource($cashBack);

        Inertia::setRootView("bot");

        return Inertia::render('Admin/AdminMain', [
            'user' => json_decode($user->toJson()),
            'botUser' => json_decode($botUser->toJson()),
            'cashBack' => json_decode($cashBack->toJson()),
        ]);

    }

    public function workDay($botDomain, $userId)
    {

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $userId)
            ->first();

        $user = new \App\Http\Resources\UserResource($botUser->user);
        $botUser = new \App\Http\Resources\BotUserResource($botUser);

        Inertia::setRootView("bot");

        return Inertia::render('Admin/AdminWorkStatus', [
            'user' => json_decode($user->toJson()),
            'botUser' => json_decode($botUser->toJson()),
        ]);
    }

    public function statistic($botDomain, $userId)
    {

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $userId)
            ->first();

        $bot = new \App\Http\Resources\BotResource($bot);
        $botUser = new \App\Http\Resources\BotUserResource($botUser);

        $statistics = [
            "users_in_bd" => BotUser::query()
                ->where("bot_id", $bot->id)
                ->count(),
            "users_in_bd_today" => BotUser::query()
                ->where("bot_id", $bot->id)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "vip_in_bd" => BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_vip", true)
                ->count(),
            "vip_in_bd_today" => BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_vip", true)
                ->whereDate('updated_at', Carbon::today())
                ->count(),
            "admin_in_bd" => BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_admin", true)
                ->count(),
            "work_admin_in_bd" => BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_admin", true)
                ->where("is_work", true)
                ->count(),
            "summary_cashback" => CashBack::query()
                ->where("bot_id", $bot->id)
                ->sum("amount"),
            "cashback_day_up" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("operation_type", 1)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_up_level_1" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("operation_type", 1)
                ->where("level", 1)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_up_level_2" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("operation_type", 1)
                ->where("level", 2)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_up_level_3" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("operation_type", 1)
                ->where("level", 3)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_day_down" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("operation_type", 0)
                ->whereDate('updated_at', Carbon::today())
                ->sum("amount"),
            "cashback_summary_up" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_up_level_1" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_up_level_2" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_up_level_3" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->sum("amount"),
            "cashback_summary_down" => CashBackHistory::query()
                ->where("bot_id", $bot->id)
                ->where("operation_type", 0)
                ->sum("amount")
        ];

        Inertia::setRootView("bot");

        return Inertia::render('Admin/AdminStatistic', [
            'bot' => json_decode($bot->toJson()),
            'botUser' => json_decode($botUser->toJson()),
            'statistic' => $statistics,
        ]);
    }

    public function promotion($botDomain, $userId)
    {

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $userId)
            ->first();

        $bot = new \App\Http\Resources\BotResource($bot);
        $botUser = new \App\Http\Resources\BotUserResource($botUser);

        return Inertia::render('Admin/AdminPromotion', [
            'bot' => json_decode($bot->toJson()),
            'botUser' => json_decode($botUser->toJson()),
        ]);
    }

    public function loadActiveAdminList(Request $request)
    {
        $request->validate([
            "bot_domain" => "required"
        ]);

        $botDomain = $request->bot_domain;

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $size = $request->get("size") ?? config('app.results_per_page');

        $botUsers = BotUser::query()
            ->with(["user"])
            ->where("bot_id", $bot->id)
            ->where("is_work", true)
            ->where("is_admin", true)
            ->paginate($size);


        return BotUserResource::collection($botUsers);

    }

    public function requestCashBack(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "user_telegram_chat_id" => "required",
            "admin_telegram_chat_id" => "required",
        ]);


        $bot = Bot::query()
            ->where("id", $request->bot_id)
            ->first();

        $tmp_user_id = (string)$request->user_telegram_chat_id;

        $code = base64_encode("001" . $tmp_user_id);
        $url_link = "https://t.me/" . $bot->bot_domain . "?start=$code";

        $adminBotUser = BotUser::query()
            ->where("telegram_chat_id", $request->admin_telegram_chat_id)
            ->where("bot_id", $bot->id)
            ->first();

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->where("bot_id", $bot->id)
            ->first();

        $message = $request->message ?? null;

        if ($adminBotUser->is_work && $adminBotUser->is_admin) {
            $name = BotMethods::prepareUserName($userBotUser);

            $typeText = $request->type == 0? "начисление бонусный баллов CashBack":
                "бронирование столика или другое действие";

            $phone = $request->phone ?? null;

            $text =
                ("Пользователь <b> $name</b> запросил у вас $typeText") .
                (is_null($message) ? "" : "\nСообщение для вас: <b>$message</b>\n").
                (is_null($phone) ? "" : "\nНомер телефона для связи: <b>$phone</b>\n");

            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendInlineKeyboard(
                    $adminBotUser->telegram_chat_id,
                    $text,
                    [
                        [
                            ["text" => "Действие с пользователем", "url" => "$url_link"]
                        ]
                    ],
                );


            $name = BotMethods::prepareUserName($adminBotUser);

            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendMessage(
                    $userBotUser->telegram_chat_id,
                    "Администратор <b>$name</b> получил ваш запрос на зачисление CashBack"
                );

        } else {
            $name = BotMethods::prepareUserName($adminBotUser);

            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendMessage(
                    $userBotUser->telegram_chat_id,
                    "Администратор <b>$name</b> на текущий момент не доступен! Попробуйте позже или же обратитесь к другому администратору!",
                );
        }


        return response()->noContent();
    }

    public function addCashBack(Request $request)
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "bot_id" => "required",
            "cashback" => "required",
            "info" => "required",
            "tg" => "required",
        ]);

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        $adminBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($userBotUser) || is_null($adminBotUser))
            return \response()->noContent(404);

        event(new CashBackEvent(
            (int)$request->bot_id,
            (int)$userBotUser->user->id,
            (int)$adminBotUser->user->id,
            ((float)$request->cashback ?? 0),
            $request->info,
            CashBackDirectionEnum::Crediting
        ));

        return \response()->noContent();
    }

    public function removeCashBack(Request $request)
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "bot_id" => "required",
            "cashback" => "required",
            "info" => "required",
            "tg" => "required",
        ]);

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        $adminBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($userBotUser) || is_null($adminBotUser))
            return \response()->noContent(404);

        event(new CashBackEvent(
            (int)$request->bot_id,
            (int)$userBotUser->user->id,
            (int)$adminBotUser->user->id,
            ((float)$request->cashback ?? 0),
            $request->info,
            CashBackDirectionEnum::Debiting
        ));

        return response()->noContent();
    }

    public function addAdmin(Request $request)
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "bot_id" => "required",
            "info" => "required",
            "tg" => "required",
        ]);

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        $adminBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($userBotUser) || is_null($adminBotUser))
            return response()->noContent(404);


        if (!$adminBotUser->is_admin) {
            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendMessage(
                    $adminBotUser->telegram_chat_id,
                    "Вы не являетесь администратором данного бота! Данное действие недоступно!"
                );

            return response()->noContent(400);
        }

        $userBotUser->is_admin = true;
        $userBotUser->save();

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendSlugKeyboard(
                $userBotUser->telegram_chat_id,
                "Вас назначили администратором данного бота!Повторно запустите команду /start",
                "main_menu_restaurant_3"
            );


        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы успешно назанчили администратором <b>$name</b>",
            );

        return response()->noContent();
    }

    public function removeAdmin(Request $request)
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
            "bot_id" => "required",
            "info" => "required",
            "tg" => "required",
        ]);

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        $adminBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($userBotUser) || is_null($adminBotUser))
            return response()->noContent(404);


        if (!$adminBotUser->is_admin) {
            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendMessage(
                    $adminBotUser->telegram_chat_id,
                    "Вы не являетесь администратором данного бота! Данное действие недоступно!",
                );
            return response()->noContent(400);
        }

        $userBotUser->is_admin = false;
        $userBotUser->save();

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendSlugKeyboard(
                $userBotUser->telegram_chat_id,
                "Вас разжаловали с должности администратора, теперь вам недоступны административные возможности",
                (!$userBotUser->is_vip ?
                    "main_menu_restaurant_1" :
                    "main_menu_restaurant_2")
            );

        $name = BotMethods::prepareUserName($userBotUser);

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы успешно убрали статус администратора у пользовтеля <b>$name</b>",
            );

        return response()->noContent();
    }

    public function selfRemoveAdmin(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "tg" => "required",
        ]);


        $adminBotUser = BotUser::query()
            ->with(["bot"])
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();


        if (is_null($adminBotUser))
            return response()->noContent(404);


        if (!$adminBotUser->is_admin) {
            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendMessage(
                    $adminBotUser->telegram_chat_id,
                    "Вы не являетесь администратором данного бота! Данное действие недоступно!"
                );

            return response()->noContent(400);
        }

        $adminBotUser->is_admin = false;
        $adminBotUser->save();

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendSlugKeyboard(
                $adminBotUser->telegram_chat_id,
                "Вас разжаловали с должности администратора, теперь вам недоступны административные возможности",
                (!$adminBotUser->is_vip ?
                    "main_menu_restaurant_1" :
                    "main_menu_restaurant_2")
            );

        return response()->noContent();
    }

    public function workStatus(Request $request)
    {
        $request->validate([
            "bot_id" => "required",
            "tg" => "required",
        ]);


        $adminBotUser = BotUser::query()
            ->with(["bot"])
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($adminBotUser))
            return response()->noContent(404);

        if (!$adminBotUser->is_admin) {
            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendMessage(
                    $adminBotUser->telegram_chat_id,
                    "Вы не являетесь администратором данного бота! Данное действие недоступно!"
                );

            return response()->noContent(400);
        }

        $adminBotUser->is_work = !$adminBotUser->is_work;
        $adminBotUser->save();

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendMessage(
                $adminBotUser->telegram_chat_id,
                "Вы изменили свой рабочий статус на <b>" . ($adminBotUser->is_work ? "Работаю" : "Не работаю") . "</b>.".
                ($adminBotUser->is_work ? "Теперь вас МОГУТ выбирать для работы с CashBack":"Теперь вас НЕ могут выбирать для работы с CashBack"),
            );

        return response()->noContent();
    }

    public function getBotAdminMenu()
    {

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_admin) {
            BotManager::bot()->reply("Вы не являетесь администратором данного бота!");
            return;
        }

        $bot_domain = BotManager::bot()->getSelf()->bot_domain;
        $selfId = BotManager::bot()->getCurrentChatId();

        BotManager::bot()->replyInlineKeyboard("Меню администратора", [
            [
                ["text" => "\xF0\x9F\x94\xB8Управление рабочим днем",
                    "web_app" => [
                        "url" => env("APP_URL") . "/admin/work-day/$bot_domain/$selfId"
                    ]
                ],
            ],
            [
                ["text" => "\xF0\x9F\x94\xB8Перейти в админку бота",
                    "web_app" => [
                        "url" => env("APP_URL") . "/admin/$bot_domain/$selfId"
                    ]
                ],
            ],

            [
                ["text" => "\xF0\x9F\x94\xB8Реклама в боте", "web_app" => [
                    "url" => env("APP_URL") . "/promotion/$bot_domain/$selfId"
                ]],
            ],
            [
                ["text" => "\xF0\x9F\x94\xB8Статистика", "web_app" => [
                    "url" => env("APP_URL") . "/statistic/$bot_domain/$selfId"
                ]],
            ]
        ]);


    }

    public function deliverymanStore(Request $request){
        $request->validate([
            "bot_id" => "required",
            "tg" => "required",
            "form.name" => "required",
            "form.phone" => "required",
            //"form.email" => "required",
            "form.birthday" => "required",
            "form.city" => "required",
            //"form.country" => "required",
            //"form.address" => "required",
            "form.sex" => "required",
        ]);

        $form = $request->form;
        $form["birthday"] = Carbon::parse($form["birthday"])
            ->format("Y-m-d");

        $form["sex"] = $form["sex"] === "on" ? 1 : 0;

        $botUser = BotUser::query()
            ->where("bot_id", $request->bot_id)
            ->where("telegram_chat_id", $request->tg["id"])
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $botUser->update($form);

        $botUser->age = Carbon::now()->year - Carbon::parse($botUser->birthday)
                ->year;
        $botUser->is_vip = true;
        $botUser->is_deliveryman = true;
        $botUser->save();

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendSlugKeyboard(
                $botUser->telegram_chat_id,
                "Вы стали нашим <b>Доставщиком</b>! Поздравляем!",
                "main_menu_deliveryman_1"
            );
        return response()->noContent();
    }

    public function vipStore(Request $request)
    {

        $request->validate([
            "bot_id" => "required",
            "tg" => "required",
            "form.name" => "required",
            "form.phone" => "required",
            //"form.email" => "required",
            "form.birthday" => "required",
            "form.city" => "required",
            //"form.country" => "required",
            //"form.address" => "required",
            "form.sex" => "required",
        ]);

        $form = $request->form;
        $form["birthday"] = Carbon::parse($form["birthday"])
            ->format("Y-m-d");

        $form["sex"] = $form["sex"] === "on" ? 1 : 0;

        $botUser = BotUser::query()
            ->where("bot_id", $request->bot_id)
            ->where("telegram_chat_id", $request->tg["id"])
            ->first();

        if (is_null($botUser))
            return response()->noContent(404);

        $botUser->update($form);

        $botUser->age = Carbon::now()->year - Carbon::parse($botUser->birthday)
                ->year;
        $botUser->is_vip = true;
        $botUser->save();

        BotMethods::bot()
            ->whereId($request->bot_id)
            ->sendSlugKeyboard(
                $botUser->telegram_chat_id,
                "Вы стали нашим <b>V.I.P.</b> пользователем! Поздравляем!",
                "main_menu_restaurant_2"
            );
        return response()->noContent();

    }

    public function vipFormDeliveryman($botDomain) {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $bot = new \App\Http\Resources\BotResource($bot);

        Inertia::setRootView("bot");

        return Inertia::render('DeliveryManForm', [
            'bot' => json_decode($bot->toJson()),
        ]);
    }

    public function vipForm($botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $bot = new \App\Http\Resources\BotResource($bot);

        Inertia::setRootView("bot");

        return Inertia::render('VipForm', [
            'bot' => json_decode($bot->toJson()),
        ]);

    }

    public function acceptUserInLocation(Request $request){
        $request->validate([
            "user_telegram_chat_id" => "required",
            "bot_id" => "required",
            "info" => "required",
            "tg" => "required",
        ]);

        $userBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->where("bot_id", $request->bot_id)
            ->first();

        $adminBotUser = BotUser::query()
            ->with(["user"])
            ->where("telegram_chat_id", $request->tg["id"])
            ->where("bot_id", $request->bot_id)
            ->first();

        if (is_null($userBotUser) || is_null($adminBotUser))
            return response()->noContent(404);


        if (!$adminBotUser->is_admin) {
            BotMethods::bot()
                ->whereId($request->bot_id)
                ->sendMessage(
                    $adminBotUser->telegram_chat_id,
                    "Вы не являетесь администратором данного бота! Данное действие недоступно!",
                );
            return response()->noContent(400);
        }

        $userBotUser->user_in_location = true;
        $userBotUser->location_comment = $request->info ?? null;
        $userBotUser->save();

        return response()->noContent();
    }
}
