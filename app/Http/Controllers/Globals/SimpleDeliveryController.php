<?php

namespace App\Http\Controllers\Globals;

use App\Classes\BotMethods;
use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class SimpleDeliveryController extends SlugController
{
    public function config(Bot $bot)
    {
        $mainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_simple_delivery_main")
            ->first();

        if (is_null($mainScript))
            return;


        $params = [

            [
                "type" => "text",
                "key" => "payment_info",
                "value" => "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>"
            ],
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Наш магазин!",

            ],

            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Перейти",

            ],


        ];

        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }

        BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_simple_delivery_main",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],

            [
                'command' => ".*Мини-доставка",
                'comment' => "Скрипт добавляет возможность заказа товара на доставку",
            ]);

    }


    public function simpleDeliveryScript(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Сервис доставки";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Открыть магазин";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard("$mainText",
                [
                    [
                        ["text" => "$btnText", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/delivery-main"
                        ]],
                    ],

                ]);

    }
}
