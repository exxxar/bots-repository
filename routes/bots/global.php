<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\NewsBotController;


BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\WheelOfFortuneScriptController::class)
    ->slug("global_wheel_of_fortune", "wheelOfFortune"); //колесо фортуны

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\WheelOfFortuneCustomScriptController::class)
    ->slug("global_wheel_of_fortune_custom", "wheelOfFortune"); //колесо фортуны 2

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\SimpleDeliveryController::class)
    ->route("/next_order ([0-9]+)", "nextOrders")
    ->route("/watch_for_deliveryman ([0-9]+)", "watchForDeliveryman")
    ->slug("global_simple_delivery_my_orders", "myOrders")
    ->slug("global_simple_delivery_main", "simpleDeliveryScript");


BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\ProfileFormScriptController::class)
    ->slug("global_profile_form_main", "profileFormMain");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\BonusProductScriptController::class)
    ->slug("global_bonus_product", "bonusProduct");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\AppointmentScriptController::class)
    ->slug("global_appointment_main", "appointmentService");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\QuizScriptController::class)
    ->slug("global_start_quiz", "startQuizForm");



BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\InstagramQuestScriptController::class)
    ->slug("global_instagram_quest", "instagramQuest"); //колесо фортуны

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\ShopScriptController::class)
    ->slug("global_shop_main", "shopMain");


BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\ScheduleBotScriptController::class)
    ->slug("global_schedule_bot_main", "scheduleBotMain");



BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\SinglePaymentScriptController::class)
    ->slug("global_single_payment_main", "singlePaymentMain");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\GeoScriptController::class)
    ->slug("global_geo_main", "geoScript");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\VenueScriptController::class)
    ->slug("global_venue_main", "venueScript");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\SendContactScriptController::class)
    ->slug("global_send_contact_main", "sendContactScript");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\FastRequestScriptController::class)
    ->route("/request_callback_without_page ([0-9]+)", "requestCallback")
    ->route("/request_callback ([0-9]+) ([0-9]+)", "requestCallback")
    ->slug("global_fast_request_main", "fastRequest");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\SimpleShopScriptController::class)
    ->route("/next_global_products ([0-9]+) ([0-9]+)", "nextProductPage")
    ->route("/detail_global_product ([0-9]+)", "detailProduct")
    ->route("/add_to_basket ([0-9]+) ([0-9]+) ([0-9]+)", "addToBasket")
    ->route("/remove_from_basket ([0-9]+)", "removeFromBasket")
    ->route("/category_products ([0-9]+) ([0-9]+)", "productsInCategory")
    ->route("/next_category_products ([0-9]+)", "nextCategories")
    ->route("/clear_basket", "clearBasket")
    ->slug("global_simple_shop", "main")
    ->slug("global_products_categories", "categories")
    ->slug("global_order_history", "orders")
    ->slug("global_products_menu", "products")
    ->slug("global_products_in_basket", "productsInBasket")
    ->slug("global_start_order", "startOrder")
    ->slug("global_clear_basket", "clearBasket")
    ->slug("global_product_basket", "basket");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\RequestMoneyWithdrawScriptController::class)
    ->slug("global_cash_out_main", "moneyWithdrawScript");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\InstantCashBackController::class)
    ->route("/take_cashback_by_slug ([0-9]+)", "takeCashBack")
    ->slug("global_start_cashback_bonus", "instantCashBack");


BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\ManagerScriptController::class)
    ->route("/next_clients ([0-9]+)", "nextClient")
    ->route("/next_bots_all ([0-9]+)", "nextBot")
    ->route("/manager_payments", "payments")
    ->route("/next_bots_by_company ([0-9]+) ([0-9]+)", "nextBotByCompany")
    ->slug("global_manager_profile", "profile")
    ->slug("global_manager_partners", "partners")
    ->slug("global_manager_clients", "clients")
    ->slug("global_manager_bots", "bots")
    ->slug("global_manager_main", "managerScript");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\CashBackScriptController::class)
    ->slug("global_cashback_main", "specialCashBackSystem")
    ->slug("global_cashback_request", "requestCashBack")
    ->slug("global_cashback_budget", "myBudget")
    ->slug("global_cashback_write_offs", "writeOffs")
    ->slug("global_cashback_charges", "charges")
    ->route("/admins", "admins");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\BookATableScriptController::class)
    ->slug("global_cashback_book_table", "bookTable");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\FriendsScriptController::class)
    ->slug("global_friends_main", "inviteFriends");

BotManager::bot()
    ->controller(\App\Http\Controllers\Globals\AboutBotScriptController::class)
    ->slug("global_about_bot_main", "aboutBot");


   /* ->slug("global_instagram_quest", "instagramQuest") //квест Instagram
    ->slug("global_cashback_module_client", "cashbackClient") //модуль кэшбэка для клиента
    ->slug("global_cashback_module_admin", "cashbackAdmin") //модуль кэшбэка для админа
    ->slug("global_shop_module_client", "shopClient") //модуль кэшбэка для админа
    ->slug("global_shop_module_admin", "shopAdmin"); //модуль кэшбэка для админа*/

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
