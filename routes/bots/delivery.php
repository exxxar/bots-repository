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
    ->controller(DeliveryServiceController::class)
    ->slug("slug_deliveryman_main_menu_1", "main")
    ->slug("slug_deliveryman_orders_1", "orders")
    ->slug("slug_deliveryman_profile_1", "profile")
    ->slug("slug_deliveryman_statistics_1", "statistic")
    ->slug("slug_deliveryman_statistics_delivery_1", "statisticDelivery")
    ->slug("slug_deliveryman_statistics_money_1", "statisticMoney")
    ->slug("slug_deliveryman_current_orders_1", "currentOrders")
    ->slug("slug_deliveryman_finished_orders_1", "finishedOrders")
    ->slug("slug_deliveryman_support_1", "support")
    ->slug("slug_deliveryman_rules_1", "rules")
    ->slug("slug_deliveryman_search_orders_1", "searchOrders");

