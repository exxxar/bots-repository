<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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


}
