<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class AboutBotScriptController extends Controller
{
    const SCRIPT = "global_about_bot_main";

    public function callbackFormGet(Request $request, $botDomain){
        Inertia::setRootView("bot");

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();
        return Inertia::render('BotPages/CallBackForm', [
            'bot' => $bot,
        ]);
    }


    public function aboutBot(...$config)
    {
        $bot = BotManager::bot()->getSelf();
        BotManager::bot()
            ->replyPhoto("Хочешь такой же бот для своего бизнеса? ",
                InputFile::create(public_path() . "/images/cashman.jpg"),
                [
                    [
                        [
                            "text" => "🔥Перейти в нашего бота для заявок",
                            "url" => "https://t.me/cashman_dn_bot"
                        ]
                    ],
                    [
                        [
                            "text" => "\xF0\x9F\x8D\x80Написать в тех. поддержку",
                            "web_app" => [
                                "url" => env("APP_URL") . "/global-scripts/about-bot/callback/" . $bot->bot_domain
                            ]
                        ],
                    ],

                ]
            );

    }
}
