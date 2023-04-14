<?php

use \App\Facades\BotManager;
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

BotManager::bot()
    ->controller(RestaurantBotController::class)
    ->route("/start ([0-9a-zA-Z=]+)", "startWithParam")
    ->route("/location ([0-9]+)", "locationInfo")
    ->route("/start", "start")
    ->slug("slug_main_menu_1", "start")
    ->slug("slug_location_1", "location")
    ->slug("slug_menu_1", "menu")
    ->slug("slug_about_us_1", "aboutUs")
    ->slug("slug_about_bot_1", "aboutBot")
    ->slug("slug_vip_form_1", "vipForm")
    ->slug("slug_special_cashback_system_1", "specialCashBackSystem")
    ->slug("slug_call_the_waiter_1", "callTheWaiter")
    ->route("/cashback", "myBudget")
    ->slug("slug_my_budget_1", "myBudget")
    ->slug("slug_request_cash_back_1", "requestCashBack")
    ->slug("slug_network_of_friends_1", "friendsNetwork")
    ->slug("slug_our_establishments_1", "establishments")
    ->slug("slug_book_a_table_1", "bookTable")
    ->slug("slug_charges_1", "charges")
    ->slug("slug_write_offs_1", "writeOffs")
    ->slug("slug_invite_friends_1", "inviteFriends")
    ->slug("slug_my_friends_1", "myFriends");

BotManager::bot()
    ->controller(NewsBotController::class)
    ->slug("slug_news_1", "getNews");

BotManager::bot()
    ->route("/.*Мой id|.*мой id", function (...$data){
        BotManager::bot()
            ->reply("Ваш чат id: ".($data[0]->chat->id??'не указан'));
    });

BotManager::bot()
    ->controller(ShopBotController::class)
    ->slug("slug_view_products_1", "viewProducts")
    ->slug("slug_product_categories_1", "productCategories");

BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

