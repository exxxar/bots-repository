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

    public function reply($message)
    {
        return $this->sendMessage($this->chatId, $message);
    }

    public function replyPhoto($caption, $path, $keyboard = [])
    {
        return $this->sendPhoto($this->chatId, $caption, $path, $keyboard);
    }

    public function replyAction($action = "typing")
    {
        return $this->sendPhoto($this->chatId, $action);
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

    public function replyEditInlineKeyboard($messageId, $keyboard)
    {
        return $this->editInlineKeyboard($this->chatId, $messageId, $keyboard);
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

    public function replyKeyboard($message, $keyboard = [])
    {
        return $this->sendReplyKeyboard($this->chatId, $message, $keyboard);
    }


    public function replyDocument($caption, $path, $filename = 'locations.pdf')
    {
        return $this->sendDocument($this->chatId, $caption, InputFile::createFromContents($path, $filename));
    }

    public function replyInlineKeyboard($message, $keyboard = [])
    {
        return $this->sendInlineKeyboard($this->chatId, $message, $keyboard);

    }

    public function replyMediaGroup($media = [])
    {
        return $this->sendMediaGroup($this->chatId, json_encode($media));
    }
}
