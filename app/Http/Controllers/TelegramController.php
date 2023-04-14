<?php

namespace App\Http\Controllers;

use App\Facades\BotManager;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function registerWebhooks(Request $request){
        return response()->json(BotManager::bot()->setWebhooks());
    }

    public function handler(Request $request, $domain)
    {
        BotManager::bot()->handler($domain);
    }
}
