<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\FileUpload\InputFile;

class SinglePaymentScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_single_payment_main")
            ->first();


        if (is_null($hasMainScript))
            return;

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_single_payment_main",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Оплата услуг",
                'comment' => "Модуль оплаты одной услуги или товара",
            ]);

        if ( empty($model->config ?? [])) {
            $model->config = [
                [
                    "type" => "text",
                    "key" => "product_price",
                    "value" => 10000,

                ],
                [
                    "type" => "text",
                    "key" => "product_description",
                    "value" => "Описание товара",

                ],
                [
                    "type" => "text",
                    "key" => "product_title",
                    "value" => "Товар",

                ],
                [
                    "type" => "text",
                    "key" => "btn_text",
                    "value" => "Оплатить",

                ],
                [
                    "type" => "boolean",
                    "key" => "need_name",
                    "value" => true,

                ],
                [
                    "type" => "boolean",
                    "key" => "need_phone_number",
                    "value" => true,

                ],
                [
                    "type" => "boolean",
                    "key" => "need_email",
                    "value" => false,

                ],
                [
                    "type" => "boolean",
                    "key" => "need_shipping_address",
                    "value" => false,

                ],
                [
                    "type" => "boolean",
                    "key" => "need_send_email_to_provider",
                    "value" => false,

                ],
                [
                    "type" => "boolean",
                    "key" => "need_send_phone_number_to_provider",
                    "value" => false,

                ],
                [
                    "type" => "boolean",
                    "key" => "is_flexible",
                    "value" => false,

                ],
                [
                    "type" => "boolean",
                    "key" => "need_disable_notification",
                    "value" => false,

                ],
                [
                    "type" => "boolean",
                    "key" => "need_protect_content",
                    "value" => false,

                ],
                [
                    "type" => "text",
                    "key" => "payload_data",
                    "value" => "Товар",

                ]
            ];
            $model->save();
        }

    }


    public function singlePaymentMain(...$config)
    {

        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Оплатить";

        $title = (Collection::make($config[1])
            ->where("key", "product_title")
            ->first())["value"] ?? "Товар";

        $payloadData = (Collection::make($config[1])
            ->where("key", "payload_data")
            ->first())["value"] ?? "Товар";

        $description = (Collection::make($config[1])
            ->where("key", "product_description")
            ->first())["value"] ?? "Описание товара";

        $price = (Collection::make($config[1])
            ->where("key", "product_price")
            ->first())["value"] ?? 10000;


        if ($price < 10000) {
            \App\Facades\BotManager::bot()
                ->reply("Вы неверно указали цену товара для осуществления оплаты!");

            return;
        }

        $prices = [
            [
                "label" => $title,
                "amount" => $price
            ]
        ];
        $payload = bin2hex(Str::uuid());

        $providerToken = $bot->payment_provider_token;
        $currency = "RUB";

        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $price,
            'status' => 0,
            'products_info' => (object)[
                "payload" => $payloadData ?? null,
                "prices" => $prices,
            ],
        ]);

        $needs = [
            "need_name" => (Collection::make($config[1])
                    ->where("key", "need_name")
                    ->first())["value"] ?? false,
            "need_phone_number" => (Collection::make($config[1])
                    ->where("key", "need_phone_number")
                    ->first())["value"] ?? false,
            "need_email" => (Collection::make($config[1])
                    ->where("key", "need_email")
                    ->first())["value"] ?? false,
            "need_shipping_address" => (Collection::make($config[1])
                    ->where("key", "need_shipping_address")
                    ->first())["value"] ?? false,
            "send_phone_number_to_provider" => (Collection::make($config[1])
                    ->where("key", "need_send_phone_number_to_provider")
                    ->first())["value"] ?? false,
            "send_email_to_provider" => (Collection::make($config[1])
                    ->where("key", "need_send_email_to_provider")
                    ->first())["value"] ?? false,
            "is_flexible" => (Collection::make($config[1])
                    ->where("key", "is_flexible")
                    ->first())["value"] ?? false,
            "disable_notification" => (Collection::make($config[1])
                    ->where("key", "disable_notification")
                    ->first())["value"] ?? false,
            "protect_content" => (Collection::make($config[1])
                    ->where("key", "protect_content")
                    ->first())["value"] ?? false,
        ];


        $keyboard = [
            [
                ["text" => $btnText, "pay" => true],
            ],

        ];

        \App\Facades\BotManager::bot()
            ->replyInvoice(
                $title, $description, $prices, $payload, $providerToken, $currency, $needs, $keyboard
            );
    }
}
