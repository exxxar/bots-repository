<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\Company;
use App\Models\ReferralHistory;
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

        BotMenuSlug::query()->updateOrCreate(
            [
                'bot_id' => $bot->id,
                'slug' => "global_manager_clients",
                'is_global' => true,
            ],
            [
                'command' => ".*Клиенты менеджера",
                'comment' => "Отображение списка клиентов менеджера",
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [
                'bot_id' => $bot->id,
                'slug' => "global_manager_profile",
                'is_global' => true,
            ],
            [
                'command' => ".*Профиль менеджера",
                'comment' => "Отображение анкеты или рабочего профиля менеджера",
            ]);
    }

    public function profile(...$config)
    {

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();


        if (!$botUser->is_manager) {

            \App\Facades\BotManager::bot()
                ->replyPhoto("Заполни эту анкету и стань менеджером",
                    InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-form"
                            ]],
                        ],

                    ]);

            return;

        }

        \App\Facades\BotManager::bot()
            ->replyPhoto("Профиль менеджера",
                InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Открыть", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-profile"
                        ]],
                    ],

                ]);


    }

    public function clients(...$config)
    {

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        if (!$botUser->is_manager) {

            \App\Facades\BotManager::bot()
                ->replyPhoto("Заполни эту анкету и стань менеджером",
                    InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-form"
                            ]],
                        ],

                    ]);

            return;

        }

        \App\Facades\BotManager::bot()
            ->replyPhoto("Список ваших клиентов",
                InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Открыть список клиентов", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-clients"
                        ]],
                    ],

                ]);


    }


    public function getFriendList(Request $request)
    {

        $bot = Bot::query()->find(21);//$request->bot;
        $botUser = BotUser::query()->find(182);//$request->botUser;

        $userIds = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->get()
            ->pluck("user_recipient_id");


        $botUsersLevel1 = BotUser::query()
            ->whereIn("user_id", $userIds)
            ->where("bot_id", $bot->id)
            ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
            ->get();


        $userIdsLevel2 = ReferralHistory::query()
            ->whereIn("user_sender_id", $botUsersLevel1->pluck("user_id"))
            ->where("bot_id", $bot->id)
            ->get()
            ->pluck("user_recipient_id");

        $botUsersLevel2 = BotUser::query()
            ->whereIn("user_id", $userIdsLevel2)
            ->where("bot_id", $bot->id)
            ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
            ->get();


        $userIdsLevel3 = ReferralHistory::query()
            ->whereIn("user_sender_id", $botUsersLevel2->pluck("user_id"))
            ->where("bot_id", $bot->id)
            ->get()
            ->pluck("user_recipient_id");

        $botUsersLevel3 = BotUser::query()
            ->whereIn("user_id", $userIdsLevel3)
            ->where("bot_id", $bot->id)
            ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
            ->get();

        $tmp = $botUsersLevel1->toArray();

        $level1Index = 0;
        foreach ($tmp as $level1) {

            if (!isset($tmp[$level1Index]["child"]))
                $tmp[$level1Index]["child"] = [];

            $level2Index = 0;
            $tmpLevel2 = $botUsersLevel2->toArray() ?? [];

            $test1 = [];
            foreach ($tmpLevel2 as $level2) {


                if (!isset($tmpLevel2[$level2Index]["child"]))
                    $tmpLevel2[$level2Index]["child"] = [];


                $tmpLevel3 = $botUsersLevel3->toArray() ?? [];
                $level3Index = 0;
                $test2 = [];

                foreach ($tmpLevel3 as $level3) {
                    if ($tmpLevel2[$level2Index]["id"] === $tmpLevel3[$level3Index]["parent_id"]) {
                        $test2[] = $tmpLevel3[$level3Index];
                    }


                    $level3Index++;
                }

                $tmpLevel2[$level2Index]["child"] = $test2;

                if ($tmp[$level1Index]["id"] == $tmpLevel2[$level2Index]["parent_id"]) {
                    $test1[] = $tmpLevel2[$level2Index];
                }


                $level2Index++;
            }

            $tmp[$level1Index]["child"] = $test1;

            $level1Index++;

        }

        return response()->json($tmp);
    }

    public function loadData(Request $request)
    {
        $slug = $request->slug;
        $bot = $request->bot;
        $botUser = $request->botUser;

        $companyIds = Company::query()
            ->where("creator_id", $botUser->id)->pluck("id");

        $botIds = Bot::query()->whereIn("company_id", $companyIds)->pluck("id");

        $clientsCount = Company::query()->where("creator_id", $botUser->id)->count();
        $botsCount = Bot::query()->whereIn("company_id", $companyIds)->count();


        return [
            "clients_count" => $clientsCount,
            "bots_count" => $botsCount,
            "ref_code" =>  base64_encode("001" . $botUser->telegram_chat_id),
            "bot_users_count" => BotUser::query()->whereIn("bot_id", $botIds)->count(),
            'free_client_slot_count' => $botUser->manager->max_company_slot_count - $clientsCount,
            'free_bot_slot_count' => $botUser->manager->max_bot_slot_count - $botsCount
        ];


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

        $bot = BotManager::bot()->getSelf();

        if (!$botUser->is_manager) {

            \App\Facades\BotManager::bot()
                ->replyPhoto("Заполни эту анкету и стань менеджером",
                    InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-form"
                            ]],
                        ],

                    ]);

            return;

        }


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
