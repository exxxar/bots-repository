<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class ManagerScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_manager_main")
            ->first();


        if (is_null($hasMainScript))
            return;


        $hasMainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_about_bot_main",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Кабинет менеджера",
                'comment' => "Запуск кабинета менеджера в боте",
            ]);

        $params = [
            [
                "type" => "image",
                "key" => "image",
                "value" => null,
            ],

        ];

        if (count($hasMainScript->config ?? []) != count($params)) {
            $hasMainScript->config = $params;
            $hasMainScript->save();
        }
    }


    public function managerScript(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $image = (Collection::make($config[1])
            ->where("key", "image")
            ->first())["value"] ?? null;


        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_manager) {
            BotManager::bot()
                ->reply("Вы не менеджер!");
            return;
        }

        if ($botUser->is_manager) {
            $bot = BotManager::bot()->getSelf();

            \App\Facades\BotManager::bot()
                ->replyPhoto("Кабинет менеджера к вашим услугам",
                    InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Приступить к работе", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-main"
                            ]],
                        ],

                    ]);


        }

    }
}
