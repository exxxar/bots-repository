<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\NewsBotController;
use App\Http\Controllers\Bots\RestaurantBotController;


BotManager::bot()
    ->controller(\App\Http\Controllers\GlobalScriptsController::class)
    ->slug("global_wheel_of_fortune", "wheelOfFortune"); //колесо фортуны

    /*->slug("global_about_us", "start") //о нас
    ->slug("global_about_bot", "start") //о нас

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
    ->slug("slug_my_friends_1", "myFriends")
    ->slug("slug_charity_1", "charities")
    ->slug("slug_search_friends_1", "searchFriends");*/

BotManager::bot()
    ->controller(NewsBotController::class)
    ->slug("slug_news_1", "getNews");
