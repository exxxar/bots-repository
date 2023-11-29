<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Telegram\Bot\FileUpload\InputFile;

class BonusProductScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->whereNull("parent_slug_id")
            ->whereNull("bot_id")
            ->where("slug", "global_bonus_product")
            ->first();


        if (is_null($hasMainScript))
            return;

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_bonus_product",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Бонус за покупку",
                'comment' => "Накапливай бонусные товары и обменивай их на реальный!",
            ]);

        $params =  [
            [
                "type" => "text",
                "key" => "max_attempts",
                "value" => 6,

            ],
            [
                "type" => "text",
                "key" => "current_attempt",
                "value" => 0,

            ],
            [
                "type" => "text",
                "key" => "icon",
                "value" => "<i class='fa-solid fa-mug-hot'></i>",

            ],
            [
                "type" => "color",
                "key" => "icon_color",
                "value" => "#a52a2a",

            ],
            [
                "type" => "boolean",
                "key" => "reloadable",
                "value" => true,

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
                "key" => "btn_text",
                "value" => "К заданию",

            ],
        ];

        if (count($model->config ?? []) != count($params)) {
            $model->config = $params;
            $model->save();
        }

    }

    public function check(Request $request)
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
        ]);

        $bot = $request->bot;
        $adminBotUser = $request->botUser;

        $slug = $request->slug;

        $userBotUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->first();

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            $bot->main_channel ??
            env("BASE_ADMIN_CHANNEL");

        $action = ActionStatus::query()
            ->where("user_id", $userBotUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            return response()->noContent();

        if ($action->current_attempts < $action->max_attempts) {
            $action->current_attempts++;
            $action->save();
        }

        $name = $userBotUser->name ?? $userBotUser->fio_from_telegram ??'Без имени';
        $phone = $userBotUser->phone ?? 'Без номера телефона';

        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($userBotUser
                ->telegram_chat_id,
                "Вам начислен 1 бонус. Сейчас у вас $action->current_attempts из $action->max_attempts необходимых. Как только вы соберете нужное колличество вы сможете обменять бонусы на приз!")
            ->sendMessage($callbackChannel,
                "Участника $name ($phone ) получил 1 бонус")
            ->sendMessage($adminBotUser->telegram_chat_id,
                "Вы начислили 1 бонус для пользователя $name ($phone )");

        return response()->noContent();
    }

    public function exchange(Request $request)
    {
        $request->validate([
            "phone" => "required",
            "name" => "required",
            "user_telegram_chat_id" => "required",
        ]);

        $bot = $request->bot;
        $adminBotUser = $request->botUser;
        $slug = $request->slug;

        $userBotUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->first();

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            $bot->main_channel ??
            env("BASE_ADMIN_CHANNEL");

        $action = ActionStatus::query()
            ->where("user_id", $userBotUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            return response()->noContent();

        if ($action->current_attempts >= $action->max_attempts) {
            $tmp = is_null($action->data) ? (object)[
                "reload_count" => 0,
                "reload_dates" => [],
                "reload_by" => $adminBotUser->telegram_chat_id,
                "name" => null,
                "phone" => null,
            ] : (object)$action->data;

            $tmp->reload_count = $tmp->reload_count + 1;
            $tmp->reload_dates[] = Carbon::now();
            $tmp->name = $request->name;
            $tmp->phone = $request->phone;

            $userBotUser->name = $userBotUser->name ?? $tmp->name;
            $userBotUser->phone = $userBotUser->phone ?? $tmp->phone;

            $action->completed_at = Carbon::now();
            $action->data = $tmp;
            $userBotUser->save();

            $reloadable = (Collection::make($slug->config)
                ->where("key", "reloadable")
                ->first())["value"] ?? false;

            $maxAttempts = (Collection::make($slug->config)
                ->where("key", "max_attempts")
                ->first())["value"] ?? 6;

            if ($reloadable) {
                $action->current_attempts = 0;
                $action->max_attempts = $maxAttempts;
            }

            $action->save();
        }

        $name = $userBotUser->name ?? $userBotUser->fio_from_telegram ??'Без имени';
        $phone = $userBotUser->phone ?? 'Без номера телефона';

        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($userBotUser
                ->telegram_chat_id,
                "Вы приняли участие в накоплении бонусов и успешно обменяли свои бонусы на приз:)")
            ->sendMessage($callbackChannel,
                "Участника $name ($phone ) обменял свои бонусы на продукт!")
            ->sendMessage($adminBotUser->telegra_chat_id,
                "Вы начислили обменяли бонусы пользователя $name ($phone ) на приз!");

        return response()->noContent();
    }

    public function loadActionData(Request $request)
    {
        $request->validate([
            "user_telegram_chat_id" => "required",
        ]);

        $bot = $request->bot;

        $adminBotUser = $request->botUser;

        $slug = $request->slug;

        $userBotUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->user_telegram_chat_id)
            ->first();

        if (is_null($userBotUser))
            return response()->noContent(404);

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 6;

        $action = ActionStatus::query()
            ->with(["slug"])
            ->where("user_id", $userBotUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $userBotUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0,
                    'data' => (object)[
                        "reload_count" => 0,
                        "reload_dates" => []
                    ]
                ]);


        $icon = (Collection::make($slug->config)
            ->where("key", "icon")
            ->first())["value"] ?? "<i class='fa-solid fa-mug-hot'></i>";

        $iconColor = (Collection::make($slug->config)
            ->where("key", "icon_color")
            ->first())["value"] ?? 'red';

        $reloadable = (Collection::make($slug->config)
            ->where("key", "reloadable")
            ->first())["value"] ?? false;

        return response()->json([
            "action" => new ActionStatusResource($action),
            'icon' => $icon ?? null,
            'icon_color' => $iconColor ?? null,
            'reloadable' => $reloadable ,
        ]);
    }

    public function prepare(Request $request)
    {

        $bot = $request->bot;

        $botUser = $request->botUser;

        $slug = $request->slug;

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 6;

        $reloadable = (Collection::make($slug->config)
            ->where("key", "reloadable")
            ->first())["value"] ?? false;

        $rules = (Collection::make($slug->config)
            ->where("key", "rules_text")
            ->first())["value"] ?? 'Правила использования';

        $icon = (Collection::make($slug->config)
            ->where("key", "icon")
            ->first())["value"] ?? "<i class='fa-solid fa-mug-hot'></i>";

        $iconColor = (Collection::make($slug->config)
            ->where("key", "icon_color")
            ->first())["value"] ?? 'red';

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
                    'current_attempts' => 0,
                    'data' => (object)[
                        "reload_count" => 0,
                        "reload_dates" => []
                    ]
                ]);

        if ($reloadable && $action->current_attempts == $action->max_attempts) {
            $action->current_attempts = 0;
            $action->max_attempts = $maxAttempts;

            $data = (object)$action->data;
            $data->reload_count = isset($data->reload_count) ? $data->reload_count + 1 : 0;
            $data->reload_dates[] = isset($data->reload_dates) ? Carbon::now() : [Carbon::now()];

            $action->data = $data;
            $action->save();
        }


        return response()->json([
            "action" => new ActionStatusResource($action),
            'rules' => $rules["value"] ?? null,
            'icon' => $icon ?? null,
            'icon_color' => $iconColor ?? null,
        ]);
    }

    public function bonusProduct(...$config)
    {

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_vip) {
            $bot = BotManager::bot()->getSelf();

            BotManager::bot()
                ->replyPhoto("Заполни эту анкету чтоб получить возможность накапливать бонусы",
                    InputFile::create(public_path() . "/images/cashman-save-up.png"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/vip"
                            ]],
                        ],

                    ]);

            return;
        }

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Получай дополнительную выгоду";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Получить";


        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/cashman-save-up.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#save-up"
                        ]],
                    ],

                ]);
    }
}
