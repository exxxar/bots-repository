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

    public function getFiles($companySlug, $file){
        $path = 'app/public/companies/'.$companySlug."/".$file;
        if (file_exists($path))
            $path = storage_path($path);
        else
            $path = public_path()."/images/cashman.jpg";
        return response()->download($path);
    }
}
