<?php

namespace App\Classes;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use Illuminate\Support\Facades\Log;

trait BotBaseMethodsTrait
{

    public function sendMessage($chatId, $message)
    {
        $tmp = [
            "chat_id" => $chatId,
            "text" => $message,
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendMessage($tmp);

        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }
        return $this;
    }

    public function sendLocation($chatId, $lat, $lon)
    {

        $tmp = [
            "chat_id" => $chatId,
            "latitude" => $lat,
            "longitude" => $lon,
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendLocation($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }
        return $this;

    }

    public function sendVenue($chatId, $lat, $lon, $address, $title)
    {

        $tmp = [
            "chat_id" => $chatId,
            "latitude" => $lat,
            "longitude" => $lon,
            "title" => $title,
            "address" => $address,
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendVenue($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }
        return $this;

    }

    public function sendContact($chatId, $phoneNumber, $firstName, $lastName = null, $vcard = null)
    {

        $tmp = [
            "chat_id" => $chatId,
            "phone_number" => $phoneNumber,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "vcard" => $vcard,
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendContact($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }
        return $this;

    }

    public function sendDocument($chatId, $caption, $path)
    {
        $tmp = [
            "chat_id" => $chatId,
            "document" => $path,
            "caption" => $caption,
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendDocument($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendReplyKeyboard($chatId, $message, $keyboard)
    {


        $tmp = [
            "chat_id" => $chatId,
            "text" => $message,
            "parse_mode" => "HTML",
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'input_field_placeholder' => "Выбор действия"
            ])
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendMessage($tmp);


        } catch (\Exception $e) {
            unset($tmp['reply_markup']);
            $this->sendMessage($chatId, $message);

        }

        return $this;

    }

    public function sendInvoice($chatId, $title, $description, $prices, $payload, $providerToken, $currency, $needs, $keyboard, $providerData = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "title" => $title,
            "description" => $description,
            "payload" => $payload,
            "provider_token" => $providerToken ?? env("PAYMENT_PROVIDER_TOKEN"),
            "provider_data" => $providerData,
            "currency" => $currency ?? env("PAYMENT_PROVIDER_CURRENCY"),
            "prices" => $prices,
            ...$needs,
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard,
            ])
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendInvoice($tmp);
        } catch (\Exception $e) {
            Log::info("Ошибка конфигурации платежной системы:" . $e->getMessage());
        }

        return $this;

        //[
        //                ["label"=>"Test", "amount"=>10000]
        //            ]
    }

    public function editInlineKeyboard($chatId, $messageId, $keyboard)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_id" => $messageId,
            "parse_mode" => "HTML",
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard,
            ])
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->editMessageReplyMarkup($tmp);
        } catch (\Exception $e) {

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;
    }

    public function editMessageMedia($chatId, $messageId, $media, $keyboard = [])
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_id" => $messageId,
            "media" => $media,
            "parse_mode" => "HTML",
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard,
            ])
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->editMessageMedia($tmp);
        } catch (\Exception $e) {

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;
    }

    public function editMessageCaption($chatId, $messageId, $caption, $keyboard = [])
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_id" => $messageId,
            "caption" => $caption,
            "parse_mode" => "HTML",
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard,
            ])
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->editMessageCaption($tmp);
        } catch (\Exception $e) {

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;
    }

    public function sendInlineKeyboard($chatId, $message, $keyboard)
    {

        $tmp = [
            "chat_id" => $chatId,
            "text" => $message,
            "parse_mode" => "HTML",
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard,
            ])

        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendMessage($tmp);

        } catch (\Exception $e) {

           // unset($tmp['reply_markup']);
           $this->sendMessage($chatId, $message);

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;
    }


    public function sendVideoNote($chatId, $videoNotePath, $keyboard = [], $keyboardType = "inline")
    {
        $tmpKeyboard = $keyboardType == "reply" ?
            [
                'reply_markup' => json_encode([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'input_field_placeholder' => "Выбор действия"
                ])
            ] :
            [
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard,
                ])
            ];

        $tmp = [
            "chat_id" => $chatId,
            "video_note" => $videoNotePath,
            "parse_mode" => "HTML",
            ...$tmpKeyboard
        ];


        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendVideoNote($tmp);
        } catch (\Exception $e) {

            unset($tmp['reply_markup']);
            $this->bot->sendPhoto($tmp);

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendPhoto($chatId, $caption, $path, $keyboard = [])
    {
        $tmp = [
            "chat_id" => $chatId,
            "photo" => $path,
            "caption" => $caption,
            "parse_mode" => "HTML",
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard,
            ])
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendPhoto($tmp);

        } catch (\Exception $e) {

            //unset($tmp['reply_markup']);
            $this->bot->sendPhoto($tmp);

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function answerPreCheckoutQuery($preCheckoutQueryId, $ok = true, $errorMessage = '')
    {
        $tmp = [
            "pre_checkout_query_id" => $preCheckoutQueryId,
            "ok" => $ok,
            "error_message" => $errorMessage,
        ];

        try {
            $this->bot->answerPreCheckoutQuery($tmp);
        } catch (\Exception $e) {

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function answerShippingQuery($shippingQueryId, $ok = true, $shippingOptions = null, $errorMessage = '')
    {
        $tmp = [
            "shipping_query_id" => $shippingQueryId,
            "ok" => $ok,
            "error_message" => $errorMessage,
        ];

        if (!is_null($shippingOptions))
            $tmp["shipping_options"] = $shippingOptions;

        try {
            $this->bot->answerShippingQuery($tmp);
        } catch (\Exception $e) {

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendMediaGroup($chatId, $media = [])
    {

        $tmp = [
            "chat_id" => $chatId,
            "media" => $media,
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendMediaGroup($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendAnswerInlineQuery($inlineQueryId, $buttons = [])
    {

        try {
            $this->bot->answerInlineQuery([
                'cache_time' => 300,
                'is_personal' => true,
                "inline_query_id" => $inlineQueryId,
                "results" => json_encode($buttons)
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendSlugKeyboard($chatId, $text, $menuSlug)
    {
        $bot = Bot::query()
            ->where('bot_domain', $this->domain)
            ->first();

        $menu = BotMenuTemplate::query()
            ->where("bot_id", $bot->id)
            ->where("slug", $menuSlug)
            ->where("type", "reply")
            ->first();

        $this->sendReplyKeyboard($chatId, $text, is_null($menu) ? [] : $menu->menu);
    }

    public function sendSlugInlineKeyboard($chatId, $text, $menuSlug)
    {
        $bot = Bot::query()
            ->where('bot_domain', $this->domain)
            ->first();

        $menu = BotMenuTemplate::query()
            ->where("bot_id", $bot->id)
            ->where("slug", $menuSlug)
            ->where("type", "inline")
            ->first();

        $this->sendInlineKeyboard($chatId, $text, is_null($menu) ? [] : $menu->menu);
    }
}
