<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;

class ShopBotController extends Controller
{
    //

    public function viewProducts(){
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("viewProducts", "cashback_buttons_1");
    }

    public function productCategories(){
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("productCategories", "cashback_buttons_1");
    }


    public function makeOrder(){
        BotManager::bot()
            ->reply("makeOrder");
    }

    public function basket(){
        BotManager::bot()
            ->reply("basket");
    }


    public function orderDeliverymanWatcher(){
        BotManager::bot()
            ->reply("orderDeliverymanWatcher");
    }

    public function specialOffers(){
        BotManager::bot()
            ->reply("specialOffers");
    }

    public function technicalSupport(){
        BotManager::bot()
            ->reply("technicalSupport");
    }

    public function orderStatusWatch(){
        BotManager::bot()
            ->reply("orderStatusWatch");
    }


    public function requestDeliverymanLocation(){
        BotManager::bot()
            ->reply("requestDeliverymanLocation");
    }

}
