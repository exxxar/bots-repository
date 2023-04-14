<?php

namespace App\Classes;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use Illuminate\Support\Facades\Log;

trait BotBaseMethodsTrait
{
    public function sendMessage($chatId, $message)
    {
        try {
            $this->bot->sendMessage([
                "chat_id" => $chatId,
                "text" => $message,
                "parse_mode" => "HTML"
            ]);
        } catch (\Exception $e) {

        }

        return $this;

    }

    public function sendLocation($chatId, $lat, $lon)
    {
        try {
            $this->bot->sendLocation([
                "chat_id" => $chatId,
                "latitude" => $lat,
                "longitude" => $lon,
                "parse_mode" => "HTML"
            ]);
        } catch (\Exception $e) {

        }
        return $this;

    }

    public function sendDocument($chatId, $caption, $path)
    {
        try {
            $this->bot->sendDocument([
                "chat_id" => $chatId,
                "document" => $path,
                "caption" => $caption,
                "parse_mode" => "HTML"
            ]);
        } catch (\Exception $e) {

        }

        return $this;

    }

    public function sendReplyKeyboard($chatId, $message, $keyboard)
    {

        try {
            $this->bot->sendMessage([
                "chat_id" => $chatId,
                "text" => $message,
                "parse_mode" => "HTML",
                'reply_markup' => json_encode([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'input_field_placeholder' => "Выбор действия"
                ])

            ]);

        } catch (\Exception $e) {

        }

        return $this;

    }

    public function sendInvoice($chatId, $title, $description, $prices, $data)
    {
        try {
            $this->bot->sendInvoice([
                "chat_id" => $chatId,
                "title" => $title,
                "description" => $description,
                "payload" => $data,
                "provider_token" => env("PAYMENT_PROVIDER_TOKEN"),
                "currency" => env("PAYMENT_PROVIDER_CURRENCY"),
                "prices" => $prices,
            ]);
        } catch (\Exception $e) {

        }

        return $this;

        //[
        //                ["label"=>"Test", "amount"=>10000]
        //            ]
    }

    public function editInlineKeyboard($chatId, $messageId, $keyboard)
    {
        try {
            $this->bot->editMessageReplyMarkup([
                "chat_id" => $chatId,
                "message_id" => $messageId,
                "parse_mode" => "HTML",
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard,
                ])

            ]);
        } catch (\Exception $e) {

        }

        return $this;
    }

    public function sendInlineKeyboard($chatId, $message, $keyboard)
    {

        try {
            $this->bot->sendMessage([
                "chat_id" => $chatId,
                "text" => $message,
                "parse_mode" => "HTML",
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard,
                ])

            ]);
        } catch (\Exception $e) {

        }

        return $this;
    }

    public function sendPhoto($chatId, $caption, $path, $keyboard = [])
    {
        try {
            $this->bot->sendPhoto([
                "chat_id" => $chatId,
                "photo" => $path,
                "caption" => $caption,
                "parse_mode" => "HTML",
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard,
                ])
            ]);
        } catch (\Exception $e) {

        }

        return $this;

    }

    public function sendMediaGroup($chatId, $media = [])
    {

        try {
            $this->bot->sendMediaGroup([
                "chat_id" => $chatId,
                "media" => $media,
            ]);
        } catch (\Exception $e) {
            Log::info($e);
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
            Log::info($e);
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
