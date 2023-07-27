<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class AboutBotScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_about_bot_main")
            ->first();


        if (is_null($hasMainScript))
            return;

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_about_bot_main",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*О боте",
                'comment' => "Инфорамция о текущем боте",
            ]);

        if (empty($model->config ?? [])) {
            $model->config = null;
            $model->save();
        }

    }

    public function callbackFormGet(Request $request, $botDomain)
    {
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
