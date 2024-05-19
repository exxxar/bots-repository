<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class PaymentSBPScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_payment_sbp_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Оплата по СБП",
                'comment' => "Позволяет пользователю оплачивать товар по СБП",
            ]);

        $params = [

            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Вы можете оплатить вашу покупку по СБП",

            ],

            [
                "type" => "image",
                "key" => "image",
                "description" => "Изображение к модулю",
                "value" => null,
            ],
            [
                "type" => "text",
                "key" => "amount",
                "description" => "Сумма к оплате",
                "value" => 0,
            ],
            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Оплатить",

            ],

        ];

        $model->config = $params;
        $model->save();

    }

    public function getProviders(){
        $response = Http::get("https://qr.nspk.ru/proxyapp/c2bmembers.json");
        return $response->json("dictionary");
    }



    public function paymentSBPScriptRun(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $image = (Collection::make($config[1])
            ->where("key", "image")
            ->first())["value"] ?? null;

        $amount = (Collection::make($config[1])
            ->where("key", "amount")
            ->first())["value"] ?? 0;

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Оплата товара";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "Оплатить";

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();


        $keyboard = [
            [
                ["text" => "$btnText", "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/payment-sbp-main/$amount"
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
