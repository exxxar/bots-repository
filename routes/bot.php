<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\InlineBotController;
use Telegram\Bot\FileUpload\InputFile;

include_once "bots/cashback.php";
include_once "bots/shop.php";
include_once "bots/delivery.php";
include_once "bots/funnel.php";
include_once "bots/manages.php";
include_once "bots/admin.php";
include_once "bots/global.php";

BotManager::bot()
    ->route("/.*Мой id|.*мой id", function (...$data){
        BotManager::bot()
            ->reply("Ваш чат id: ".($data[0]->chat->id??'не указан'));
    });

BotManager::bot()
    ->route("/democircle", function (...$data){
        BotManager::bot()
            ->replyVideoNote(
                InputFile::create(public_path() . "/videos/vid1.mp4"),[
                    [
                        ["text"=>"Главное меню","callback_data"=>"/start"]
                    ]
                ]
            );
    });


BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

