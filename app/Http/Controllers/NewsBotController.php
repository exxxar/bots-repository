<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsBotController extends Controller
{
    public function getNews(){
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("getNews", "cashback_buttons_1");
    }
}
