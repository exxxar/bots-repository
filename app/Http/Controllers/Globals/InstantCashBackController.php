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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use ReflectionClass;
use Telegram\Bot\FileUpload\InputFile;

class InstantCashBackController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_start_cashback_bonus")
            ->first();


        if (is_null($hasMainScript))
            return;

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_start_cashback_bonus",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Получить CashBack мгновенно!",
                'comment' => "Модуль получения мгновенного CashBack",
            ]);

            $params = [
                [
                    "type" => "text",
                    "key" => "max_attempts",
                    "value" => 2,

                ],
                [
                    "type" => "text",
                    "key" => "cashback_amount",
                    "value" => 0,

                ],
                [
                    "type" => "text",
                    "key" => "main_text",
                    "value" => "Вам доступно для получения <b>%s</b> руб CashBack",

                ],
                [
                    "type" => "text",
                    "key" => "after_text",
                    "value" => "Вы уже получили данный бонус, на текущий момент это всё:)",

                ],

                [
                    "type" => "text",
                    "key" => "btn_text",
                    "value" => "\xF0\x9F\x8E\xB2Получить",

                ],

            ];

        if (count($model->config ?? []) != count($params)) {
            $model->config = $params;
            $model->save();
        }

    }

    public function takeCashBack(...$data)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $slugId = $data[3] ?? null;

        $slug = BotMenuSlug::query()->find($slugId);

        if (is_null($slug)) {
            BotManager::bot()->reply("Скрипт не доступен!");
            return;
        }

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slugId)
            ->first();

        if (is_null($action)) {
            BotManager::bot()->reply("Данное действие недоступно!");
            return;
        }

        $cashBackAmount = (Collection::make($slug->config)
            ->where("key", "cashback_amount")
            ->first())["value"] ?? 0;

        $admin = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("is_admin", true)
            ->orderBy("updated_at", "desc")
            ->first();

        if (is_null($admin)) {
            BotManager::bot()->reply("Данная функция временно недоступна!");
            return;
        }


        BusinessLogic::administrative()
            ->setBot($bot)
            ->setBotUser($admin)
            ->addCashBack([
                "user_telegram_chat_id" => $botUser->telegram_chat_id,
                "amount" => $cashBackAmount,
                "percent"=>100,
                "info" => "Мгновенное начисление CashBack в размере $cashBackAmount руб.",
            ]);

        $action->current_attempts++;
        $action->save();

    }

    public function instantCashBack(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"] ?? "route";

        if ($slugId == "route") {
            BotManager::bot()->reply("Скрипт не доступен!");
            return;
        }

        $slug = BotMenuSlug::query()->find($slugId);

        if (is_null($slug)) {
            BotManager::bot()->reply("Скрипт не доступен!");
            return;
        }

        $cashBackAmount = (Collection::make($config[1])
            ->where("key", "cashback_amount")
            ->first())["value"] ?? 0;

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slugId)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slugId,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0
                ]);

        if ($action->current_attempts >= $action->max_attempts) {

            $afterText = (Collection::make($config[1])
                ->where("key", "after_text")
                ->first())["value"] ?? "Вы уже воспользовались данной возможностью!)";

            BotManager::bot()->reply($afterText);
            return;
        }

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Вам доступно для получения <b>%s</b> руб CashBack";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Получить";

        \App\Facades\BotManager::bot()
            ->replyPhoto(sprintf($mainText, $cashBackAmount),
                InputFile::create(public_path() . "/images/cashman.jpg"),
                [
                    [
                        ["text" => $btnText, "callback_data" => "/take_cashback_by_slug $slugId"],
                    ],
                ]);
    }
}
