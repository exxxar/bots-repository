<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\ShopBotController;


BotManager::bot()
    ->controller(ShopBotController::class)
    ->slug("slug_view_products_1", "viewProducts")
    ->slug("slug_make_order_1", "makeOrder")
    ->slug("slug_basket_1", "basket")
    ->slug("slug_order_deliveryman_watcher_1", "orderDeliverymanWatcher")
    ->slug("slug_special_offers_1", "specialOffers")
    ->slug("slug_technical_support_1", "technicalSupport")
    ->slug("slug_order_status_watch_1", "orderStatusWatch")
    ->slug("slug_request_deliveryman_location_1", "requestDeliverymanLocation")
    ->slug("slug_product_categories_1", "productCategories");

BotManager::bot()
    ->controller(\App\Http\Controllers\TableController::class)
    ->route("/request_table_join ([0-9]+) ([0-9]+)", "requestTableJoin")
    ->route("/test_sbp_tinkoff_automatic ([0-9]+) ([0-9]+)", "testSbpTinkoffAutomatic")
    ->route("/test_table_manual_payment ([0-9]+) ([0-9]{1})", "testTableManualPayment")
    ->route("/accept_table_join ([0-9]+) ([0-9]+) ([0-9]+)", "acceptTableJoin");

BotManager::bot()
    ->controller(\App\Http\Controllers\Bots\Web\ProductController::class)
    ->route("/test_invoice_sbp_tinkoff_automatic ([0-9]+) ([0-9]+)", "testSbpTinkoffAutomatic");

BotManager::bot()
    ->controller(\App\Http\Controllers\Bots\FoodBasketController::class)
    ->route("/test_foods_sbp_tinkoff_automatic ([0-9]+) ([0-9]+)", "testSbpTinkoffAutomatic")
    ->route("/test_foods_manual_payment ([0-9]+) ([0-9]{1})", "testManualPayment");

BotManager::bot()
    ->controller(\App\Http\Controllers\Bots\GoodsBasketController::class)
    ->route("/test_goods_sbp_tinkoff_automatic ([0-9]+) ([0-9]+)", "testSbpTinkoffAutomatic")
    ->route("/test_goods_manual_payment ([0-9]+) ([0-9]{1})", "testManualPayment");
