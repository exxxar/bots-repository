<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Telegram\Bot\FileUpload\InputFile;

class InstagramQuestScriptController extends Controller
{
    const SCRIPT = "global_instagram_quest";

    const KEY_MAX_ATTEMPTS = "max_attempts";
    const KEY_CALLBACK_CHANNEL_ID = "callback_channel_id";
    const KEY_RULES_TEXT = "rules_text";
    const KEY_WIN_MESSAGE = "win_message";
    const KEY_WHEEL_TEXT = "wheel_text";
    const KEY_MAIN_TEXT = "main_text";
    const KEY_BTN_TEXT = "btn_text";

    public function instagramQuest(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", self::KEY_MAIN_TEXT)
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $btnText = (Collection::make($config[1])
            ->where("key", self::KEY_BTN_TEXT)
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Заполнить анкету";

        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/cashman-quest.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/global-scripts/insta-quest/$bot->bot_domain"
                        ]],
                    ],

                ]);
    }
}
