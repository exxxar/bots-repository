<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class CashReceiptScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_cash_receipt_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Ввод номера чека",
                'comment' => "Регистрация чека и выдача бонусов (кэшбэка)",
            ]);

        $params = [

            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Вы можете ввести номер чека и получить дополнительные баллы",

            ],
            [
                "type" => "text",
                "key" => "rules_text",
                "value" => "Правила начисления бонусов",

            ],
            [
                "type" => "text",
                "key" => "bonus_amount",
                "value" => 0,
                "description" => 'Число баллов за регистрацию чека',

            ],
            [
                "type" => "boolean",
                "key" => "is_auto",
                "value" => false,
                "description" => 'Автоматическое \ ручное начисление баллов',

            ],
            [
                "type" => "image",
                "key" => "image",
                "value" => null,
            ],
            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Ввести номер чека",

            ],

        ];

        $model->config = $params;
        $model->save();

    }

    /**
     * @throws ValidationException
     */
    public function activate(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            // "bot_id" => "required",
            "code" => "required",
        ]);

        $botUser = $request->botUser;

        $bot = $request->bot;

        BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->activatePromoCode(
                $request->all()
            );

        return response()->noContent();
    }


    public function cashReceiptMain(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $image = (Collection::make($config[1])
            ->where("key", "image")
            ->first())["value"] ?? null;

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Начисление бонусов за регистрацию чека";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "Ввести номер чека";

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();


        $keyboard = [
            [
                ["text" => "$btnText", "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/promocode-main"
                ]],
            ],

        ];

        if (is_null($image))
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard("$mainText", $keyboard);
        else
            \App\Facades\BotManager::bot()
                ->replyPhoto("$mainText", $image, $keyboard);


    }
}
