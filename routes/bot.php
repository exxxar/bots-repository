<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\InlineBotController;
use Illuminate\Support\Facades\Log;
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
        Log::info(print_r($data, true));

        $caption = $data[2] ?? null;
        $photos = $data[3] ?? null;

        if (is_null($caption)){
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

        Log::info("my_photo ".print_r($photoToSend, true));
        $channel = $bot->main_channel ?? $bot->order_channel ?? null;

        if (is_null($photoToSend) || is_null($channel)) {
            BotManager::bot()->reply("Ошибка отправки фотографии!");
            return;
        }

        BotManager::bot()
            ->sendPhoto(
                $channel,
                InputFile::create(
                    "https://api.telegram.org/bot".$bot->bot_token."/getFile?file_id=$photoToSend"
                    ,"payments.jpg")
                ,
                $caption
            );


        BotManager::bot()->reply("Спасибо! Ваше фото загружено!");
    });

