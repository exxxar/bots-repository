<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Telegram\Bot\FileUpload\InputFile;

class SinglePaymentScriptController extends Controller
{
    const SCRIPT = "global_single_payment_main";

    /*    const KEY_MAX_ATTEMPTS = "max_attempts";
        const KEY_CALLBACK_CHANNEL_ID = "callback_channel_id";*/
    const KEY_PRODUCT_PRICE = "product_price";
    const KEY_PRODUCT_DESCRIPTION = "product_description";
    const KEY_PRODUCT_TITLE = "product_title";
    const KEY_BTN_TEXT = "btn_text";

    public function singlePaymentMain(...$config)
    {

        $bot = BotManager::bot()->getSelf();


        $btnText = (Collection::make($config[1])
            ->where("key", self::KEY_BTN_TEXT)
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Оплатить";

        $title = (Collection::make($config[1])
            ->where("key", self::KEY_PRODUCT_TITLE)
            ->first())["value"] ?? "Товар";

        $description = (Collection::make($config[1])
            ->where("key", self::KEY_PRODUCT_DESCRIPTION)
            ->first())["value"] ?? "Товар";

        $price = (Collection::make($config[1])
            ->where("key", self::KEY_PRODUCT_PRICE)
            ->first())["value"] ?? 10000;

        if ($price < 1000) {
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
        $payload = "1234";

        $providerToken = $bot->payment_provider_token;
        $currency = "RUB";

        $needs = [
            "need_name" => true,
            "need_phone_number" => true,
            "need_email" => false,
            "need_shipping_address" => false,
            "send_phone_number_to_provider" => false,
            "send_email_to_provider" => false,
            "is_flexible" => true,
            "disable_notification" => true,
            "protect_content" => true,
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
