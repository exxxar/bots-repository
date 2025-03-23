<?php

namespace App\Http\Controllers;

use App\Http\Resources\BotSecurityResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MobileController extends Controller
{
    public function guestMobileHomePage(Request $request, $botDomain) {
        $bot = Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Inertia::setRootView("mobile");
            return Inertia::render('V1/Error');
        }


        Inertia::setRootView("mobile");
        return Inertia::render('Mobile',[
            'bot_domain' => $botDomain,
            'pages'=>json_decode(file_get_contents(storage_path()."\\app\\".$botDomain."_pages.json")),
            'bot' => BotSecurityResource::make($bot),
        ]);
    }

    public function mobileHomePage(Request $request, $botDomain) {

        $bot = Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Inertia::setRootView("mobile");
            return Inertia::render('V1/Error');
        }

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("user_id",auth()->user()->id)
            ->first();

        Inertia::share([
            'bot_domain' => $botDomain,
            'bot_user' => $botUser->toArray(),
            'bot' => BotSecurityResource::make($bot),
            'pages'=>json_decode(file_get_contents(storage_path()."app\\".$botDomain."_pages.json"))
        ]);

        Inertia::setRootView("mobile");
        return Inertia::render('Mobile');
    }
}
