<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\InlineBotController;

include_once "bots/cashback.php";
include_once "bots/shop.php";
include_once "bots/delivery.php";
include_once "bots/funnel.php";
include_once "bots/manages.php";
include_once "bots/admin.php";

BotManager::bot()
    ->route("/.*Мой id|.*мой id", function (...$data){
        BotManager::bot()
            ->reply("Ваш чат id: ".($data[0]->chat->id??'не указан'));
    });

BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

