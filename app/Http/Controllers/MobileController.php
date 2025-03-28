<?php

namespace App\Http\Controllers;

use App\Http\Resources\BotSecurityResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MobileController extends Controller
{
    public function guestMobileHomePage(Request $request, $botDomain)
    {
        $bot = Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Inertia::setRootView("mobile");
            return Inertia::render('V1/Error');
        }


        Inertia::setRootView("mobile");
        return Inertia::render('Mobile', [
            'bot_domain' => $botDomain,
            'pages' => json_decode(file_get_contents(storage_path() . "\\app\\" . $botDomain . "_pages.json")),
            'bot' => BotSecurityResource::make($bot),
        ]);
    }

    public function mobileHomePage(Request $request, $botDomain, $page)
    {
        Session::put("domain", $botDomain);

        $bot = Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Inertia::setRootView("mobile");
            return Inertia::render('V1/Error');
        }

        $botUser = null;
        if (!is_null(auth()->user() ?? null))
            $botUser = BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("user_id", auth()->user()->id)
                ->first();

        $json = Storage::get($botDomain . '_pages.json');
        $data = json_decode($json, true);

        $page = Collection::make($data)
            ->where("slug", $page)
            ->first();

        if (!is_null($page)) {
            Inertia::setRootView("mobile",);
            return Inertia::render('MobileForTemplate', [
                'bot_domain' => $botDomain,
                'bot_user' => !is_null($botUser) ? $botUser->toArray() : null,
                'bot' => BotSecurityResource::make($bot),
                'page' => $page
            ]);
        }


    }
}
