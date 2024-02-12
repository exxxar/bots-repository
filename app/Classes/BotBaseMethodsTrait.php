<?php

namespace App\Classes;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\TelegramClient;
use Telegram\Bot\TelegramRequest;

trait BotBaseMethodsTrait
{

    public function replyToMessage($chatId, $replyToMessageId, $message, $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "reply_to_message_id" => $replyToMessageId,
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

    public function testSetMyName($name)
    {

        $tmp = [
            "name" => $name,
        ];

        try {

            $botToken = "1050575583:AAEuI5StQcxhNgeXRqfo_VqUG3mzhAWt0V4";
            $website = "https://api.telegram.org/bot" . $botToken;

            Http::post("$website/setMyDescription", [
                'description' => 'This 123 is my message !!!',
            ]);
            $chatId = $this->getCurrentChatId();  //** ===>>>NOTE: this chatId MUST be the chat_id of a person, NOT another bot chatId !!!**
            $params = [
                //'chat_id'=>$chatId,
                'description' => 'This 123 is my message !!!',
            ];
            $ch = curl_init($website . '/setMyDescription');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);

            /*   $req =  new TelegramRequest();
               //$req->setAccessToken("1050575583:AAEuI5StQcxhNgeXRqfo_VqUG3mzhAWt0V4");
               $req->setMethod("setMyName")
                   ->setParams([
                       "name"=>"TEEEEEEST"
                   ]);

               // $client = new TelegramClient();
               //  $client->sendRequest($req);

               //$req->setMethod()*/


        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getLine());
        }


    }

    public function sendMessage($chatId, $message, $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
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

    public function sendSticker($chatId, $sticker, $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "sticker" => $sticker,
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendSticker($tmp);

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


    public function forwardMessage($chatId, $fromChatId, $messageId)
    {

        $tmp = [
            "chat_id" => $chatId,
            "from_chat_id" => $fromChatId,
            "message_id" => $messageId,
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->forwardMessage($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }
        return $this;
    }

    public function sendDice($chatId, $type = 0)
    {
        $emojis = ["🎲", "🎯", "🏀", "⚽", "🎳", "🎰"];

        $type = $type >= count($emojis) || $type < 0 ? 0 : $type;

        $tmp = [
            "chat_id" => $chatId,
            "emoji" => $emojis[$type],
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendDice($tmp);
            Log::info("dice result=>" . print_r($data, true));
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }
        return $this;

    }

    public function sendDocumentWithKeyboard($chatId, $caption, $fileId, $keyboard = [], $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "document" => $fileId,
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
            $this->bot->sendDocument($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }


    public function sendAudio($chatId, $caption, $fileId, $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "audio" => $fileId,
            "caption" => $caption,
            "parse_mode" => "HTML"
        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendAudio($tmp);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }


    public function sendDocument($chatId, $caption, $fileId, $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "document" => $fileId,
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

    public function sendReplyKeyboard($chatId, $message, $keyboard, $messageThreadId = null)
    {


        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "text" => $message,
            "parse_mode" => "HTML",
            'reply_markup' => !is_null($keyboard) && !empty($keyboard) ? json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'input_field_placeholder' => "Выбор действия"
            ]) : json_encode([
                'remove_keyboard' => true,
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
            "media" => json_encode($media),
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


    public function editMessageText($chatId, $messageId, $text, $keyboard = [])
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_id" => $messageId,
            "text" => $text,
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
            $this->bot->editMessageText($tmp);
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

    public function sendInlineKeyboard($chatId, $message, $keyboard, $messageThreadId = null)
    {

        $tmp = [
            "chat_id" => $chatId,
            "text" => $message,
            "message_thread_id" => $messageThreadId,
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


    public function sendChatAction($chatId, $action, $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "action" => $action,

        ];

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendChatAction($tmp);

        } catch (\Exception $e) {

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendPhoto($chatId, $caption, $path, $keyboard = [], $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
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

    public function sendVideo($chatId, $caption, $videoPath, $keyboard = [], $messageThreadId = null)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "video" => $videoPath,
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
            $data = $this->bot->sendVideo($tmp);

        } catch (\Exception $e) {

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

    public function sendAnswerInlineQuery($inlineQueryId, $results = [], $nextOffset = null, $button = null)
    {

        try {
            $this->bot->answerInlineQuery([
                'cache_time' => 300,
                'is_personal' => true,
                'next_offset' => $nextOffset,
                "inline_query_id" => $inlineQueryId,
                "results" => json_encode($results),
                "button" => $button,
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
