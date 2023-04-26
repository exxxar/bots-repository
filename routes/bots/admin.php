<?php

use \App\Facades\BotManager;
use App\Http\Controllers\DeliveryServiceController;
use App\Http\Controllers\InlineBotController;
use \App\Http\Controllers\RestaurantBotController;
use \App\Http\Controllers\NewsBotController;
use \App\Http\Controllers\AdminBotController;
use \App\Http\Controllers\ShopBotController;
use Illuminate\Support\Facades\Log;

BotManager::bot()
    ->controller(AdminBotController::class)
    ->slug("slug_news_1", "getNews")
    ->slug("slug_admin_menu_1", "getBotAdminMenu");
