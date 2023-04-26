<?php

use \App\Facades\BotManager;
use App\Http\Controllers\DeliveryServiceController;
use App\Http\Controllers\InlineBotController;
use \App\Http\Controllers\RestaurantBotController;
use \App\Http\Controllers\NewsBotController;
use \App\Http\Controllers\AdminBotController;
use \App\Http\Controllers\ShopBotController;
use Illuminate\Support\Facades\Log;

include_once "bots/cashback.php";
include_once "bots/shop.php";
include_once "bots/delivery.php";
include_once "bots/admin.php";

BotManager::bot()
    ->route("/.*Мой id|.*мой id", function (...$data){
        BotManager::bot()
            ->reply("Ваш чат id: ".($data[0]->chat->id??'не указан'));
    });

BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

