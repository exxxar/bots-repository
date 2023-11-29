<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use ReflectionClass;
use Telegram\Bot\FileUpload\InputFile;

class WheelOfFortuneCustomScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->whereNull("parent_slug_id")
            ->whereNull("bot_id")
            ->where("slug", "global_wheel_of_fortune_custom")
            ->first();


        if (is_null($hasMainScript))
            return;



        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_wheel_of_fortune_custom",

                'is_global' => true,
            ],
            [
                'command' => ".*Колесо фортуны вариант 2",
                'comment' => "Игровой модуль",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "max_attempts",
                "value" => 2,

            ],
            [
                "type" => "channel",
                "key" => "callback_channel_id",
                "value" => $bot->order_channel ?? $bot->main_channel ?? env("BASE_ADMIN_CHANNEL"),

            ],
            [
                "type" => "text",
                "key" => "rules_text",
                "value" => "Всё гениальное просто - делай фото по заданию и загружай их!",

            ],
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Принимай участие в наших квестах и получай ценные призы!",

            ],
            [
                "type" => "text",
                "key" => "win_message",
                "value" => "%s, вы приняли участие в квесте и скоро получите награду. Наш менеджер свяжется с вами в ближайшее время!",

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
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№1",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№2",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№3",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№4",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№5",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№6",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№7",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№8",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№9",

            ],
        ];

        if (count($model->config ?? []) != count($params)) {
            $model->config =$params;
            $model->save();
        }

    }

    public function formWheelOfFortuneCallback(Request $request)
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "win" => "required"
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;


        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            $bot->main_channel ??
            env("BASE_ADMIN_CHANNEL");

        $winMessage = (Collection::make($slug->config)
            ->where("key", "win_message")
            ->first())["value"] ?? "%s, вы приняли участие в розыгрыше и выиграли приз под номером %s. Наш менеджер свяжется с вами в ближайшее время!";

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0
                ]);

        $action->current_attempts++;
        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();


        $winNumber = $request->win ?? 0;
        $winnerName = $request->name ??  $botUser->name ?? 'Имя не указано';
        $winnerPhone = $request->phone ??    $botUser->phone ?? 'Телефон не указан';

        $botUser->name = $botUser->name ?? $winnerName;
        $botUser->phone = $botUser->phone ?? $winnerPhone;
        $botUser->save();

        $username = $botUser->username ?? null;

        $tmp = $action->data ?? [];


        $wheelText = Collection::make($slug->config)
            ->where("key", "wheel_text")
            ->pluck("value")
            ->toArray();

        $description = $wheelText[$winNumber] ?? 'Без описания';

        $tmp[] = (object)[
            "name" => $winnerName,
            "win" => $winNumber,
            "description" => $description,
            "phone" => $winnerPhone,
            "answered_at" => null,
            "answered_by" => null,
        ];

        $action->data = $tmp;

        $link = "https://t.me/$bot->bot_domain?start=" . base64_encode("003$botUser->telegram_chat_id");

        $action->max_attempts = $maxAttempts;
        $action->save();

        $thread = $bot->topics["actions"] ?? null;

        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($botUser
                ->telegram_chat_id,
                str_contains($winMessage, "%s") ?
                    sprintf($winMessage, $winnerName, $winNumber, $description) : $winMessage)
            ->sendInlineKeyboard($callbackChannel,
                "Участник $winnerPhone ($winnerName " . ($username ? "@$username" : 'Домен не указан') . ") принял участие в розыгрыше и выиграл приз №$winNumber ( $description ) - свяжитесь с ним для дальнейших указаний", [
                    [
                        ["text" => "Написать пользователю ответ", "url" => $link]
                    ]
                ], $thread);

        return response()->noContent();
    }

    public function loadData(Request $request)
    {

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;


        $wheels = Collection::make($slug->config)
            ->where("key", "wheel_text")
            ->toArray();

        $rules = Collection::make($slug->config)
            ->where("key", "rules_text")
            ->first();

        $callback_message = Collection::make($slug->config)
            ->where("key", "callback_message")
            ->first();

        return response()->json(
            [
                "wheels" => array_values($wheels),
                'rules' => $rules["value"] ?? null,
                'callback_message' => $rules["value"] ?? null,
            ]

        );
    }

    public function formWheelOfFortunePrepare(Request $request)
    {

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0
                ]);

        $action->max_attempts = $maxAttempts;

        if (is_null($action->data)) {
            $action->current_attempts = 0;
            $action->save();
        }


        return response()->json([
            "action" => new ActionStatusResource($action),

        ]);
    }

    public function wheelOfFortune(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Начать розыгрыш";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/cashman-wheel-of-fortune.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#wheel-of-fortune-custom"
                        ]],
                    ],

                ]);
    }
}
