<?php

namespace App\Classes;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use danog\Decoder\FileId;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
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

            $this->sendMessageOnCrash($tmp, "sendMessage");

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
        if (mb_strlen($message ?? '') == 0)
            return $this;


        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "text" => $message,
            "parse_mode" => "HTML"
        ];

        return $this->extractedMessage($message, $tmp, $chatId, $messageThreadId);
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
            $this->sendMessageOnCrash($tmp, "sendSticker");

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
            $this->sendMessageOnCrash($tmp, "sendLocation");

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
            $this->sendMessageOnCrash($tmp, "sendVenue");

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

            $this->sendMessageOnCrash($tmp, "sendContact");

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

        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }


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

            $this->sendMessageOnCrash($tmp, "sendDocument");

            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendReplyKeyboard($chatId, $message, $keyboard, $messageThreadId = null, $settings = null)
    {

        $settings = (array)$settings;

        $tmp = [
            "chat_id" => $chatId,
            "message_thread_id" => $messageThreadId,
            "text" => $message,
            "parse_mode" => "HTML",
            'reply_markup' => !is_null($keyboard) && !empty($keyboard) ? json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => $settings["resize_keyboard"] ?? true,
                'is_persistent' => $settings["is_persistent"] ?? false,
                'one_time_keyboard' => $settings["one_time_keyboard"] ?? false,
                'input_field_placeholder' => $settings["input_field_placeholder"] ?? "Выбор действия"
            ]) : json_encode([
                'remove_keyboard' => true,
            ])
        ];

        return $this->extractedMessage($message, $tmp, $chatId, $messageThreadId);

    }

    protected function sendMessageOnCrash($tmp, $func)
    {
        unset($tmp['reply_markup']);
        unset($tmp['message_thread_id']);

        if (isset($tmp["message"])) {
            if (is_null($tmp["message"] ?? null))
                return;
        }

        $tmp["message"] = mb_strlen($tmp["message"] ?? '') > 0 ? $tmp["message"] : "Главное меню";

        if (isset($tmp["photo"])) {
            $fileId = null;
            try {
                if (!is_null($tmp["photo"] ?? null) && !($tmp["photo"] instanceof InputFile))
                    $fileId = FileId::fromBotAPI($tmp["photo"]);
            } catch (Exception $e) {

            }


            $tmp["photo"] = !is_null($fileId) ? $tmp["photo"] :
                InputFile::create(public_path() . "/images/cashman.jpg");
        }

        try {
            $this->bot->{$func}($tmp);
        } catch (\Exception $e) {

            Log::error("[1]$func=>" . $e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());

            try {
                $this->bot->sendMessage([
                    "chat_id" => $tmp["chat_id"],
                    "text" => $tmp["caption"] ?? "Тут что-то должно было быть, но возникли непредвиденные обстоятельства и этого нет...",
                    "parse_mode" => "HTML"
                ]);
            } catch (\Exception $exception) {
                Log::error("[2]$func=>" . $exception->getMessage() . " " .
                    $exception->getFile() . " " .
                    $exception->getLine());
            }


        }


    }

    public function createInvoiceLink($chatId, $title, $description, $prices, $payload, $providerToken, $currency, $needs, $providerData = null)
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

        ];


        $bot = Bot::query()->where("bot_domain", $this->domain)->first();

        try {
            $client = Http::post("https://api.telegram.org/bot" . $bot->bot_token . "/createInvoiceLink", $tmp);


            return $client->json();

        } catch (\Exception $e) {
            $this->sendMessageOnCrash($tmp, "createInvoiceLink");

            Log::info("Ошибка конфигурации платежной системы:" . $e->getMessage());
        }

        return null;
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

        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        } else {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => [
                    [
                        ["text" => "Оплатить заказ", "pay" => true]
                    ]
                ],
            ]);
        }

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $this->bot->sendInvoice($tmp);
        } catch (\Exception $e) {
            $this->sendMessageOnCrash($tmp, "sendInvoice");

            Log::info("Ошибка конфигурации платежной системы:" . $e->getMessage());
        }

        return $this;

    }

    public function editInlineKeyboard($chatId, $messageId, $keyboard)
    {
        $tmp = [
            "chat_id" => $chatId,
            "message_id" => $messageId,
            "parse_mode" => "HTML",

        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }


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

        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

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

        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

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

        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }


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
            "message_thread_id" => $messageThreadId,
            "text" => $message,
            "parse_mode" => "HTML"
        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

        if (mb_strlen($message ?? '') >= 4000) {
            $subMessage = mb_substr($message, 0, 4000);
            $elseMessage = mb_substr($message, 4000);

            $tmp["text"] = "$subMessage...";

            $data = $this->bot->sendMessage($tmp);

            return $this->sendMessage($chatId, "...$elseMessage", $messageThreadId);

        }


        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendMessage($tmp);

        } catch (\Exception $e) {

            $this->sendMessageOnCrash($tmp, "sendMessage");

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

            $this->sendMessageOnCrash($tmp, "sendVideoNote");

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

            $this->sendMessageOnCrash($tmp, "sendChatAction");


            Log::error($e->getMessage() . " " .
                $e->getFile() . " " .
                $e->getLine());
        }

        return $this;

    }

    public function sendPhoto($chatId, $caption, $path, array $keyboard = [], $messageThreadId = null)
    {

        $photoIsCorrect = $path instanceof InputFile;

        try {
            if (!$photoIsCorrect) {
                $fileId = FileId::fromBotAPI($path);
                $photoIsCorrect = true;
            }
        } catch (Exception $e) {

        }

        if (!$photoIsCorrect) {
            return empty($keyboard ?? []) ?
                $this->sendMessage($chatId, $caption ?? 'Ошибочка...', $messageThreadId) :
                $this->sendInlineKeyboard($chatId, $caption ?? 'Ошибочка...', $keyboard, $messageThreadId);
        }

        $tmp = [
            "chat_id" => $chatId,
            "photo" => $path,
            "caption" => $caption,
            "parse_mode" => "HTML",
        ];

        if (!is_null($messageThreadId))
            $tmp["message_thread_id"] = $messageThreadId;

        if (count($keyboard ?? []) > 0) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

        if (mb_strlen($caption ?? '') >= 1000) {
            $subMessage = mb_substr($caption, 0, 1000);
            $elseMessage = mb_substr($caption, 1000);

            $tmp["caption"] = "$subMessage...";

            $data = $this->bot->sendPhoto($tmp);

            return $this->sendMessage($chatId, "...$elseMessage", $messageThreadId);

        }

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendPhoto($tmp);
        } catch (\Exception $e) {
            empty($keyboard ?? []) ?
                $this->sendMessage($chatId, $caption ?? 'Ошибочка...', $messageThreadId) :
                $this->sendInlineKeyboard($chatId, $caption ?? 'Ошибочка...', $keyboard, $messageThreadId);
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

        ];

        if (!empty($keyboard ?? [])) {
            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendVideo($tmp);

        } catch (\Exception $e) {

            $this->sendMessageOnCrash($tmp, "sendVideo");

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

    /**
     * @param $message
     * @param array $tmp
     * @param $chatId
     * @param mixed $messageThreadId
     * @return $this|BotManager|BotMethods
     */
    private function extractedMessage($message, array $tmp, $chatId, mixed $messageThreadId): BotBaseMethodsTrait|BotMethods|BotManager
    {
        if (mb_strlen($message ?? '') >= 4000) {
            $subMessage = mb_substr($message, 0, 4000);
            $elseMessage = mb_substr($message, 4000);

            $tmp["text"] = "$subMessage...";

            $data = $this->bot->sendMessage($tmp);

            return $this->sendMessage($chatId, "...$elseMessage", $messageThreadId);

        }


        if ($this->isWebMode) {
            $this->pushWebMessage($tmp);
            return $this;
        }

        try {
            $data = $this->bot->sendMessage($tmp);
        } catch (\Exception $e) {
            Log::info("error in extractedMessage" . $e);
            //$this->sendMessageOnCrash($tmp, "sendMessage");
        }
        return $this;
    }
}
