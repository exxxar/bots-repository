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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

        try {
            $this->bot->sendMessage($tmp);
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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

        try {
            $this->bot->sendLocation($tmp);
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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

        try {
            $this->bot->sendMessage($tmp);

        } catch (\Exception $e) {
            unset($tmp['reply_markup']);
            $this->bot->sendMessage($tmp);

        }

        return $this;

    }

    public function sendInvoice($chatId, $title, $description, $prices, $data)
    {
        $tmp = [
            "chat_id" => $chatId,
            "title" => $title,
            "description" => $description,
            "payload" => $data,
            "provider_token" => env("PAYMENT_PROVIDER_TOKEN"),
            "currency" => env("PAYMENT_PROVIDER_CURRENCY"),
            "prices" => $prices,
        ];

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

        try {
            $this->bot->sendInvoice($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

        try {
            $this->bot->editMessageReplyMarkup($tmp);
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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

        try {
            $this->bot->sendMessage($tmp);
        } catch (\Exception $e) {

            unset($tmp['reply_markup']);
            $this->bot->sendMessage($tmp);

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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

        try {
            $this->bot->sendPhoto($tmp);
        } catch (\Exception $e) {

            unset($tmp['reply_markup']);
            $this->bot->sendPhoto($tmp);

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

        $this->pushWebMessage($tmp);

        if ($this->isWebMode)
            return $this;

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
                'cache_time' => 0,
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
