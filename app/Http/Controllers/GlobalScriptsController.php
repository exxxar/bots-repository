<?php

namespace App\Http\Controllers;

use App\Facades\BotManager;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class GlobalScriptsController extends Controller
{
    //

    public function formWheelOfFortune($botDomain) {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $slug = BotMenuSlug::query()
            ->where("bot_id",$bot->id)
            ->where("slug","global_wheel_of_fortune")
            ->orderBy("updated_at", "desc")
            ->first();

        $wheels = Collection::make($slug->config)
            ->where("key","wheel_text")
            ->toArray();
        Inertia::setRootView("bot");

        return Inertia::render('BotPages/WheelOfFortune',[
            "text"=>array_values($wheels)
        ]);
    }

    public function wheelOfFortune(...$config) {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key","main_text")
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $btnText = (Collection::make($config[1])
            ->where("key","btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Заполнить анкету";


        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/cashman2.jpg"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/global-scripts/wheel-of-fortune/$bot->bot_domain"
                        ]],
                    ],

                ]);
    }
}
