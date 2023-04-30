<?php

namespace App\Http\Controllers\Bots;

use App\Http\Controllers\Controller;

class NewsBotController extends Controller
{
    public function getNews(){
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("getNews", "cashback_buttons_1");
    }
}
