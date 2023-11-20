<?php

namespace App\Classes;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use Database\Seeders\BotMenuTemplateSeeder;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

trait BotMethodsTrait
{
    use BotBaseMethodsTrait;

    public function sendReplyMenu($text, $menuSlug)
    {
        $bot = Bot::query()
            ->where('bot_domain', $this->domain)
            ->first();

        $menu = BotMenuTemplate::query()
            ->where("bot_id", $bot->id)
            ->where("slug", $menuSlug)
            ->where("type", "reply")
            ->first();

        $this->replyKeyboard($text, is_null($menu) ? [] : $menu->menu);
    }

    public function sendInlineMenu($text, $menuSlug)
    {

        $bot = Bot::query()
            ->where('bot_domain', $this->domain)
            ->first();

        $menu = BotMenuTemplate::query()
            ->where("bot_id", $bot->id)
            ->where("slug", $menuSlug)
            ->where("type", "inline")
            ->first();

        $this->sendInlineKeyboard(
            $this->getCurrentChatId(),
            $text,
            is_null($menu) ? [] : $menu->menu);
    }

    public function reply($message,  $messageThreadId = null)
    {
        return $this->sendMessage($this->chatId, $message, $messageThreadId);
    }

    public function replyPhoto($caption, $path, $keyboard = [], $messageThreadId = null)
    {
        return $this->sendPhoto($this->chatId, $caption, $path, $keyboard, $messageThreadId);
    }

    public function replyVideo($caption, $path, $keyboard = [], $messageThreadId = null)
    {
        return $this->sendVideo($this->chatId, $caption, $path, $keyboard, $messageThreadId);
    }


    public function replyAction($action = "typing", $messageThreadId = null)
    {
        return $this->sendChatAction($this->chatId, $action, $messageThreadId);
    }

    public function replyEditMessageMedia($messageId,$media, $keyboard = [])
    {
        return $this->editMessageMedia($this->chatId, $messageId, $media, $keyboard);
    }


    public function replyEditInlineKeyboard($messageId, $keyboard = [])
    {
        return $this->editInlineKeyboard($this->chatId, $messageId, $keyboard);
    }

    public function replyEditMessageCaption($messageId, $caption, $keyboard = [])
    {
        return $this->editMessageCaption($this->chatId, $messageId, $caption, $keyboard);
    }

    public function replyVideoNote($videoNotePath, $keyboard = [], $keyboardType = "inline")
    {
        return $this->sendVideoNote($this->chatId, $videoNotePath, $keyboard, $keyboardType);
    }

    public function replyPhotoWithInlineMenu($caption, $path, $menuSlug)
    {
        $bot = Bot::query()
            ->where('bot_domain', $this->domain)
            ->first();

        $menu = BotMenuTemplate::query()
            ->where("bot_id", $bot->id)
            ->where("slug", $menuSlug)
            ->where("type", "inline")
            ->first();

        return $this->sendPhoto($this->chatId, $caption, $path, is_null($menu) ? [] : $menu->menu);
    }

    public function replyDice($type = 0)
    {
        return $this->sendDice($this->chatId, $type);
    }

    public function replyLocation($lat, $lon)
    {
        return $this->sendLocation($this->chatId, $lat, $lon);
    }

    public function replyVenue($lat, $lon, $address, $title)
    {
        return $this->sendVenue($this->chatId, $lat, $lon, $address, $title);
    }

    public function replyContact($phoneNumber, $firstName, $lastName = null, $vcard = null)
    {
        return $this->sendContact($this->chatId, $phoneNumber, $firstName, $lastName, $vcard);
    }

    public function replyInvoice($title, $description, $prices, $payload, $providerToken, $currency, $needs, $keyboard, $providerData = null)
    {
        return $this->sendInvoice($this->chatId, $title, $description, $prices, $payload, $providerToken, $currency, $needs, $keyboard, $providerData);
    }

    public function replyKeyboard($message, $keyboard = [], $messageThreadId = null)
    {
        return $this->sendReplyKeyboard($this->chatId, $message, $keyboard, $messageThreadId);
    }


    public function replyDocumentWithKeyboard($caption,  $fileId, $keyboard = [], $messageThreadId = null)
    {
        return $this->sendDocumentWithKeyboard($this->chatId, $caption,
            $fileId,
            $keyboard,
            $messageThreadId
        );
    }

    public function replyDocument($caption, $path, $filename = 'locations.pdf', $messageThreadId = null)
    {
        return $this->sendDocument($this->chatId, $caption,
            InputFile::createFromContents($path, $filename),
            $messageThreadId
        );
    }

    public function replyInlineKeyboard($message, $keyboard = [], $messageThreadId = null)
    {
        return $this->sendInlineKeyboard($this->chatId, $message, $keyboard, $messageThreadId);

    }

    public function replyMediaGroup($media = [])
    {
        return $this->sendMediaGroup($this->chatId, json_encode($media));
    }
}
