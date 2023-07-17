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
    const KEY_NEED_NAME = "need_name";
    const KEY_NEED_PHONE_NUMBER = "need_phone_number";
    const KEY_NEED_EMAIL = "need_email";
    const KEY_NEED_SHIPPING_ADDRESS = "need_shipping_address";
    const KEY_NEED_SEND_EMAIL_TO_PROVIDER = "need_send_email_to_provider";
    const KEY_NEED_SEND_PHONE_NUMBER_TO_PROVIDER = "need_send_phone_number_to_provider";
    const KEY_IS_FLEXIBLE = "is_flexible";
    const KEY_DISABLE_NOTIFICATION = "need_disable_notification";
    const KEY_PROTECT_CONTENT = "need_protect_content";

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
        $payload = "1234";

        $providerToken = $bot->payment_provider_token;
        $currency = "RUB";

        $needs = [
            "need_name" => (Collection::make($config[1])
                    ->where("key", self::KEY_NEED_NAME)
                    ->first())["value"] ?? false,
            "need_phone_number" => (Collection::make($config[1])
                    ->where("key", self::KEY_NEED_PHONE_NUMBER)
                    ->first())["value"] ?? false,
            "need_email" => (Collection::make($config[1])
                    ->where("key", self::KEY_NEED_EMAIL)
                    ->first())["value"] ?? false,
            "need_shipping_address" => (Collection::make($config[1])
                    ->where("key", self::KEY_NEED_SHIPPING_ADDRESS)
                    ->first())["value"] ?? false,
            "send_phone_number_to_provider" => (Collection::make($config[1])
                    ->where("key", self::KEY_NEED_SEND_PHONE_NUMBER_TO_PROVIDER)
                    ->first())["value"] ?? false,
            "send_email_to_provider" => (Collection::make($config[1])
                    ->where("key", self::KEY_NEED_SEND_EMAIL_TO_PROVIDER)
                    ->first())["value"] ?? false,
            "is_flexible" => (Collection::make($config[1])
                    ->where("key", self::KEY_IS_FLEXIBLE)
                    ->first())["value"] ?? false,
            "disable_notification" => (Collection::make($config[1])
                    ->where("key", self::KEY_DISABLE_NOTIFICATION)
                    ->first())["value"] ?? false,
            "protect_content" => (Collection::make($config[1])
                    ->where("key", self::KEY_PROTECT_CONTENT)
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
