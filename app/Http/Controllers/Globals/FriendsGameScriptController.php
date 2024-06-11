<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\ReferralHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use ReflectionClass;
use stdClass;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class FriendsGameScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_friends_game_main",
                'parent_slug_id' => null,
                'bot_id' => null,
                'is_global' => true,
            ],
            [
                'command' => ".*Собери друзей и получи приз",
                'comment' => "Игровой модуль по сбору нужного числа друзей. Счетчик может вести отчет от общего числа и с 0",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "next_win_page_id",
                "description" => "Вызов следующей страницы при достижении нужного числа друзей (id страницы)",
                "value" => null
            ],

            [
                "type" => "image",
                "key" => "main_image",
                "value" => null,

            ],
            [
                "type" => "text",
                "key" => "max_friends_attempts",
                "description" => "Число друзей, которое нужно собрать, чтоб получить приз",
                "value" => 5,

            ],
            [
                "type" => "text",
                "key" => "cashback_for_win",
                "description" => "Начислить кэшбэк за друзей - указать сумму, 0 - не начислять",
                "value" => 0,

            ],
            [
                "type" => "script",
                "key" => "profile_id",
                "value" => null,

            ],
            [
                "type" => "channel",
                "key" => "callback_channel_id",
                "value" => $bot->order_channel ?? env("BASE_ADMIN_CHANNEL"),

            ],
            [
                "type" => "text",
                "key" => "rules_text",
                "value" => "Всё гениальное просто - приглашай друзей и получай бонусы!",

            ],
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Принимай участие в наших квестах и получай ценные призы!",

            ],

            [
                "type" => "text",
                "key" => "callback_message",
                "value" => "Спасибо за ваше участие! Вы выиграли приз!
Когда будете готовы сделать заказ, позвоните по номеру: +7 (999) 418-28-84 и при заказе, обязательно, уточните что вы выиграли в «Колесо Фортуны»",

            ],
            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "К заданию",

            ],

        ];


        $model->config = $params;
        $model->save();


    }

    /**
     * @throws ValidationException
     */
    public function finishGame(Request $request)
    {
        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_friends_attempts")
            ->first())["value"] ?? 1;

        $action = ActionStatus::prepare($botUser, $bot, $slug, $maxAttempts);


        if (is_null($action->data ?? null))
            return response()->noContent(400);

        $isStarted = $action->data->start_at ?? null;
        $isCompleted = $action->data->complete_at ?? null;

        if (is_null($isStarted) || !is_null($isCompleted))
            return response()->noContent(400);

        $refsCount = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->orderBy("created_at", "DESC")
            ->count();

        $action->data = (object)[
            "friends_invite" => abs($refsCount - ($action->data["friends_on_start"] ?? 0)),
            "current_friends" => $refsCount,
            "start_at" => $action->data["start_at"] ?? null,
            "complete_at" => $action->data["complete_at"] ?? Carbon::now()->format("Y-m-d H:i:s"),
            "friends_on_start" => $action->data["friends_on_start"] ?? 0,
            "needed_friends" => $action->data["needed_friends"] ?? 0
        ];

        $action->save();

        $callbackMessage = (Collection::make($slug->config)
            ->where("key", "callback_message")
            ->first())["value"] ?? "Спасибо за участие!";

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            env("BASE_ADMIN_CHANNEL");

        $cashBackForWin = (Collection::make($slug->config)
            ->where("key", "cashback_for_win")
            ->first())["value"] ?? 0;

        $thread = $bot->topics["actions"] ?? null;

        $link = "https://t.me/$bot->bot_domain?start=" . base64_encode("003$botUser->telegram_chat_id");

        $winnerName = $botUser->name ?? 'Имя не указано';
        $winnerPhone = $botUser->phone ?? 'Телефон не указан';
        $username = $botUser->username ?? null;

        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage(
                $botUser->telegram_chat_id,
                $callbackMessage)
            ->sendInlineKeyboard($callbackChannel,
                "Участник $winnerPhone ($winnerName " . ($username ? "@$username" : 'Домен не указан') . ") принял участие в задании по приглашению друзей - свяжитесь с ним для дальнейших указаний", [
                    [
                        ["text" => "Написать пользователю ответ", "url" => $link]
                    ]
                ], $thread);

        $nextWinPageId = (Collection::make($request->slug->config)
            ->where("key", "next_win_page_id")
            ->first())["value"] ?? null;

        if ($cashBackForWin > 0) {
            $admin = BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_admin", true)
                ->orderBy("updated_at", "desc")
                ->first();

            if (is_null($admin))
                throw new HttpException(400, "В системе нет администратора!");

            BusinessLogic::administrative()
                ->setBot($bot)
                ->setBotUser($admin)
                ->addCashBack([
                    "user_telegram_chat_id" => $botUser->telegram_chat_id,
                    "amount" => $cashBackForWin,
                    "percent" => 100,
                    "info" => "Мгновенное начисление CashBack в размере $cashBackForWin руб.",
                ]);
        }

        if (!is_null($nextWinPageId)) {
            $isRun = BotManager::bot()
                ->runPage($nextWinPageId, $bot, $botUser);

            if (!$isRun)
                BotManager::bot()
                    ->runSlug($nextWinPageId, $bot, $botUser);
        }

    }

    public function startFriendsGame(Request $request)
    {
        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_friends_attempts")
            ->first())["value"] ?? 1;

        $action = ActionStatus::prepare($botUser, $bot, $slug, $maxAttempts);

        $refsCount = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->orderBy("created_at", "DESC")
            ->count();

        if (!is_null($action->data ?? null)) {
            $isStarted = $action->data["start_at"] ?? null;
            $isCompleted = $action->data["complete_at"] ?? null;

            if (!is_null($isStarted) || !is_null($isCompleted))
                return response()->json([
                    "action" => new ActionStatusResource($action),
                    "current_friends" => $refsCount,
                    "start_at" => $action->data["start_at"] ?? Carbon::now()->format("Y-m-d H:i:s"),
                    "complete_at" => $action->data["complete_at"] ?? null,
                ]);
        }


        $action->data = (object)[
            "friends_invite" => 0,
            "start_at" => Carbon::now()->format("Y-m-d H:i:s"),
            "complete_at" => null,
            "current_friends" => $refsCount,
            "friends_on_start" => $refsCount,
            "needed_friends" => $refsCount + $maxAttempts
        ];
        $action->save();


        return response()->json([
            "action" => new ActionStatusResource($action),
            "current_friends" => $refsCount,
        ]);
    }

    public function friendsGamePrepare(Request $request)
    {

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_friends_attempts")
            ->first())["value"] ?? 1;

        $action = ActionStatus::prepare($botUser, $bot, $slug, $maxAttempts);

        $refsCount = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->orderBy("created_at", "DESC")
            ->count();

        if (!is_null($action->data ?? null)) {

            $action->data = (object)[
                "friends_invite" => abs($refsCount - ($action->data["friends_on_start"] ?? 0)),
                "current_friends" => $refsCount,
                "start_at" => $action->data["start_at"] ?? null,
                "complete_at" => $action->data["complete_at"] ?? null,
                "friends_on_start" => $action->data["friends_on_start"] ?? 0,
                "needed_friends" => $action->data["needed_friends"] ?? 0
            ];

            $action->current_attempts = min(
                $action->max_attempts, abs(
                ($action->data["needed_friends"] ?? 0) - ($action->data["current_friends"] ?? 0)));
            $action->save();
        }

        if (is_null($action->data ?? null)) {
            $action->data = (object)[
                "friends_invite" => 0,
                "start_at" => null,
                "complete_at" => null,
                "current_friends" => $refsCount,
                "friends_on_start" => $refsCount,
                "needed_friends" => $refsCount + $maxAttempts
            ];
            $action->save();
        }

        $rulesText = (Collection::make($slug->config)
            ->where("key", "rules_text")
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $mainImage = (Collection::make($slug->config)
            ->where("key", "main_image")
            ->first())["value"] ?? null;

        return response()->json([
            "action" => new ActionStatusResource($action),
            "current_friends" => $refsCount,
            "image" => $mainImage,
            "rules" => $rulesText,
        ]);
    }

    public function friendsGameMain(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $mainImage = (Collection::make($config[1])
            ->where("key", "main_image")
            ->first())["value"] ?? null;

        $profileScriptId = (Collection::make($config[1])
            ->where("key", "profile_id")
            ->first())["value"] ?? null;

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Начать розыгрыш";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $keyboard = [
            [
                ["text" => $btnText, "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#friends-game"
                ]],
            ],

        ];

        if (!$botUser->is_vip) {

            $bot = BotManager::bot()->getSelf();

            if (!is_null($profileScriptId)) {

                BotManager::bot()
                    ->runSlug($profileScriptId, $bot, $botUser);

            } else {
                $keyboard = [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/vip"
                        ]],
                    ],

                ];


                if (is_null($mainImage))
                    \App\Facades\BotManager::bot()
                        ->replyInlineKeyboard("Для начала необходимо заполнить анкету!", $keyboard);
                else
                    \App\Facades\BotManager::bot()
                        ->replyPhoto("Для начала необходимо заполнить анкету!", $mainImage, $keyboard);

            }

            return;
        }


        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                is_null($mainImage) ?
                    InputFile::create(public_path() . "/images/cashman.jpg") :
                    $mainImage,
                $keyboard);


    }
}
