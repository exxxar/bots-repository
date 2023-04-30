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

