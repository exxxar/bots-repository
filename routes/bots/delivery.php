<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\DeliveryServiceBotController;

BotManager::bot()
    ->controller(DeliveryServiceBotController::class)
    ->route("/take_order ([0-9]+)", "takeOrder")
    ->route("/order_success ([0-9]+)", "orderSuccess")
    ->route("/watch_order ([0-9]+)", "watchOrder")
    ->slug("slug_toggle_work_1", "toggleWork")
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

