<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\InlineBotController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\FileUpload\InputFile;

include_once "bots/cashback.php";
include_once "bots/shop.php";
include_once "bots/delivery.php";
include_once "bots/funnel.php";
include_once "bots/manages.php";
include_once "bots/admin.php";
include_once "bots/global.php";

BotManager::bot()
    ->route("/.*Мой id|.*мой id", function (...$data) {
        BotManager::bot()
            ->reply("Ваш чат id: " . ($data[0]->chat->id ?? 'не указан'));
    });

BotManager::bot()
    ->route("/democircle", function (...$data) {
        BotManager::bot()
            ->replyVideoNote(
                InputFile::create(public_path() . "/videos/vid1.mp4"), [
                [
                    ["text" => "Главное меню"]
                ]
            ],
                "reply"
            );
    });


BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

BotManager::bot()
    ->fallbackPhoto(function (...$data) {
        $caption = $data[2] ?? null;
        $photos = $data[3] ?? null;

        if (is_null($caption)) {
            BotManager::bot()->reply("Фотография в описании должна содержать подпись!");
            return;
        }

        $caption = strtolower($caption);

        if (!str_contains($caption, "оплата")) {
            BotManager::bot()->reply("Фотография в описании должна содержать ключевое слово, например: оплата");
            return;
        }

        $bot = BotManager::bot()->getSelf();
        $photoToSend = $photos[count($photos) - 1]->file_id ?? null;

        $channel = $bot->main_channel ?? $bot->order_channel ?? null;

        if (is_null($photoToSend) || is_null($channel)) {
            BotManager::bot()->reply("Ошибка отправки фотографии!");
            return;
        }

        $botUser = BotManager::bot()->currentBotUser();

        $name = \App\Facades\BotMethods::prepareUserName($botUser);

        $id = $botUser->telegram_chat_id;

        $phone = $botUser->phone ?? 'Не указан';

        $data = "001" . $botUser->telegram_chat_id;

        $link = "https://t.me/$bot->bot_domain?start=" .
            base64_encode($data);

        BotManager::bot()
            ->sendPhoto(
                $channel,
                "#оплатачеком\n" .
                "Идентификатор: $id\n" .
                "Пользователь: $name\n" .
                "телефон: $phone\n",
                $photoToSend, [
                    [
                        ["text" => "Работа с пользователем", "url" => $link]
                    ]
                ]
            );

        BotManager::bot()->reply("Спасибо! Ваше фото загружено!");
    });

