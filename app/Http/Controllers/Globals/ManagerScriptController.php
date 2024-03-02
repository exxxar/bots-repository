<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotSecurityResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\Company;
use App\Models\ReferralHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class ManagerScriptController extends SlugController
{
    public function config(Bot $bot)
    {


        $hasMainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_manager_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
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
                'slug' => "global_manager_clients",
                'is_global' => true,
            ],
            [
                'command' => ".*Клиенты менеджера",
                'comment' => "Отображение списка клиентов менеджера",
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [
                'slug' => "global_manager_bots",
                'is_global' => true,
            ],
            [
                'command' => ".*Созданные менеджером боты",
                'comment' => "Отображение списка всех созданных менеджером ботов",
            ]);

        $partnerScript = BotMenuSlug::query()->updateOrCreate(
            [
                'slug' => "global_manager_partners",
                'is_global' => true,
            ],
            [
                'command' => ".*Сеть партнеров менеджера",
                'comment' => "Отображение списка всех партнеров менеджера",
            ]);

        $params = [
            [
                "type" => "image",
                "key" => "image",
                "value" => null,
            ],

        ];

        if (count($partnerScript->config ?? []) != count($params)) {
            $partnerScript->config = $params;
            $partnerScript->save();
        }

        BotMenuSlug::query()->updateOrCreate(
            [
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

        $message = sprintf("Имя: %s
Телефон: %s
Город: %s
Колл-во слотов под ботов: %s
        ",
            $botUser->name ?? 'Не указано',
            $botUser->phone ?? 'Не указано',
            $botUser->city ?? 'Не указано',
            //$botUser->birthday ?? 'Не указано',
            // $botUser->manager->balance ?? 0,
            // $botUser->cashBack->amount ?? 0,
            //  $botUser->sex ? 'Мужской' : 'Женский',
            //  $botUser->manager->max_company_slot_count ?? 0,
            $botUser->manager->max_bot_slot_count ?? 0,
        );

        $companyDomain = $bot->company->slug;

        $path = storage_path("app/public") . "/companies/$companyDomain/" . ($botUser->manager->image ?? 'noimage.jpg');

        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/manager.png"
        );

        \App\Facades\BotManager::bot()
            ->replyPhoto("Профиль менеджера\n$message",
                $file,
                [
                    [
                        ["text" => "👨🏽‍💻Детали профиля", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-profile"
                        ]],
                    ],
                    [
                        ["text" => "💳Перейти в кабинет",
                            "login_url" => [
                                'url' => env("app_url")."/auth/tg-link"
                            ]
                        ],
                    ],
                    /*  [
                          ["text" => "💳Пополнить внутренний баланс", "callback_data" => "/manager_payments"],
                      ],
                      [
                          ["text" => "💰Запросить вывод средств", "web_app" => [
                              "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/cash-out"
                          ]],
                      ],*/


                ]);
    }

    public function payments(...$config)
    {

    }

    public function partners(...$config)
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


        $image = (Collection::make($config[1])
            ->where("key", "image")
            ->first())["value"] ?? null;

        \App\Facades\BotManager::bot()
            ->replyPhoto("Открыть информацию о партнерах",
                InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Информация о партнерах", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-partners"
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


        $client = Company::query()
            ->where("creator_id", $botUser->id)
            ->orderBy("updated_at", "desc")
            ->first();

        if (is_null($client)) {
            \App\Facades\BotManager::bot()
                ->reply("Вы еще не добавили ни 1 клиента");

            return;
        }

        $this->prepareClient($client, null, 0);


    }

    public function bots(...$config)
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


        $bot = Bot::query()
            ->whereHas("company", function ($q) use ($botUser) {
                $q->where("creator_id", $botUser->id);
            })
            ->orderBy("updated_at", "desc")
            ->first();

        if (is_null($bot)) {
            \App\Facades\BotManager::bot()
                ->reply("Вы еще не добавили ни 1 бота");

            return;
        }


        $this->prepareBots($bot, null, 0);


    }

    public function nextClient(...$data)
    {
        $messageId = $data[0]->message_id ?? null;
        $pageId = $data[3] ?? null;

        $botUser = BotManager::bot()->currentBotUser();

        $client = Company::query()
            ->where("creator_id", $botUser->id)
            ->orderBy("updated_at", "desc")
            ->take(1)
            ->skip(max(0, $pageId))
            ->first();

        if (is_null($client)) {
            if (!is_null($messageId))
                BotManager::bot()
                    ->replyEditInlineKeyboard($messageId, []);

            BotManager::bot()
                ->reply("Вы еще не добавили ни 1 клиента");
            return;
        }

        $this->prepareClient($client, $messageId, $pageId);
    }

    public function nextBotByCompany(...$data)
    {
        $messageId = $data[0]->message_id ?? null;
        $companyId = $data[4] ?? null;
        $pageId = $data[3] ?? null;

        $botUser = BotManager::bot()->currentBotUser();

        $bot = Bot::query();

        if (!is_null($companyId))
            $bot = $bot->where("company_id", $companyId);

        $bot = $bot->whereHas("company", function ($q) use ($botUser) {
            $q->where("creator_id", $botUser->id);
        })
            ->orderBy("updated_at", "desc")
            ->take(1)
            ->skip($pageId ?? 0)
            ->first();


        if (is_null($bot)) {
            if (!is_null($messageId))
                BotManager::bot()
                    ->replyEditInlineKeyboard($messageId, []);

            BotManager::bot()
                ->reply("Никаких ботов не найдено");

            return;
        }

        $this->prepareBots($bot, $messageId, $pageId, $companyId);
    }

    public function nextBot(...$data)
    {

        $messageId = $data[0]->message_id ?? null;
        $pageId = $data[3] ?? null;

        $botUser = BotManager::bot()->currentBotUser();

        $bot = Bot::query()
            ->whereHas("company", function ($q) use ($botUser) {
                $q->where("creator_id", $botUser->id);
            })
            ->orderBy("updated_at", "desc")
            ->take(1)
            ->skip($pageId ?? 0)
            ->first();


        if (is_null($bot)) {
            if (!is_null($messageId))
                BotManager::bot()
                    ->replyEditInlineKeyboard($messageId, []);

            BotManager::bot()
                ->reply("Никаких ботов не нейдано");

            return;
        }

        $this->prepareBots($bot, $messageId, $pageId);
    }

    private function prepareClient($client, $messageId = null, $page = 0)
    {
        $phones = "";

        foreach ($client->phones ?? [] as $phone)
            $phones .= "$phone\n";

        $links = "";

        foreach ($client->links ?? [] as $link)
            $links .= "$link\n";

        $path = !is_null($client->image) ?
            storage_path("app/public") . "/companies/$client->slug/" . $client->image :
            null;

        $file = InputFile::create(
            file_exists($path) ?
                env("APP_URL") . "/images-by-company-id/$client->id/$client->image" :
                env("APP_URL") . "/images/cashman.jpg"
        );

        $message = sprintf("Название клиента: %s
Описание:
<em>%s</em>
Адрес: %s
Почта: %s
Ответственный менеджер: %s
Телефоны:
<em>%s</em>
Ссылки на соц. сети:
<em>%s</em>
            ",
            $client->title,
            $client->description,
            $client->address,
            $client->email,
            $client->manager,
            strlen($phones) == 0 ? "Не добавлены" : $phones,
            strlen($links) == 0 ? "Не добавлены" : $links,
        );

        if (is_null($messageId)) {
            \App\Facades\BotManager::bot()
                ->replyPhoto("$message",
                    $file,
                    [
                        [
                            ["text" => "🤖Боты клиента", "callback_data" => "/next_bots_by_company 0 $client->id"],
                        ],
                        [
                            ["text" => "Вперед ▶", "callback_data" => "/next_clients 1"],
                        ],
                    ]);

            return;
        }


        BotManager::bot()
            ->replyEditMessageMedia(
                $messageId,
                [
                    "type" => "photo",
                    "media" => $file->getFile(),
                    "caption" => $message,
                ],
                [
                    [
                        ["text" => "🤖Боты клиента", "callback_data" => "/client_bot_list"],
                    ],
                    [
                        ["text" => "◀ Назад (" . ($page - 1) . ")", "callback_data" => "/next_client " . ($page - 1)],
                        ["text" => "Вперед (" . ($page + 1) . ") ▶", "callback_data" => "/next_client " . ($page + 1)],
                    ],
                ]
            );
    }

    private function prepareBots($bot, $messageId = null, $page = 0, $companyId = null)
    {

        $companyDomain = $bot->company->slug ?? null;


        $path = !is_null($bot->image) ?
            storage_path("app/public") . "/companies/$companyDomain/" . $bot->image :
            null;

        $file = InputFile::create(
            file_exists($path) ?
                env("APP_URL") . "/images-by-bot-id/$bot->id/$bot->image" :
                env("APP_URL") . "/images/cashman.jpg"
        );


        $text = "$bot->bot_domain (Владелец $companyDomain)";

        if (is_null($messageId)) {

            BotManager::bot()
                ->replyPhoto($text, $file, [
                    [
                        ["text" => "🤖Перейти в бот", "url" => "https://t.me/" . ($bot->bot_domain ?? 'error')],
                    ],
                    [
                        ["text" => "‍💻Диагностика бота", "callback_data" => "/diagnostic $bot->id"],
                    ],
                    [
                        ["text" => "Вперед ▶", "callback_data" => is_null($companyId) ? "/next_bots_all 1" : "/next_bots_by_company 1 $companyId"],
                    ],
                ]);
            return;
        }


        $keyboard = [
            [
                ["text" => "🤖Перейти в бот", "url" => "https://t.me/" . ($bot->bot_domain ?? 'error')],
            ],
            [
                ["text" => "💻Диагностика бота", "callback_data" => "/diagnostic $bot->id"],
            ],

        ];

        if ($page == 0)
            $keyboard[] = [
                ["text" => "Вперед ▶", "callback_data" => is_null($companyId) ? "/next_bots_all " . ($page + 1) : "/next_bots_by_company " . ($page + 1) . " $companyId"],
            ];
        if ($page > 0)
            $keyboard[] = [
                ["text" => "◀Назад (" . ($page - 1) . ")", "callback_data" => is_null($companyId) ? "/next_bots_all " . ($page - 1) : "/next_bots_by_company " . ($page - 1) . " $companyId"],
                ["text" => "Вперед (" . ($page + 1) . ") ▶", "callback_data" => is_null($companyId) ? "/next_bots_all " . ($page + 1) : "/next_bots_by_company " . ($page + 1) . " $companyId"],
            ];

        BotManager::bot()
            ->replyEditMessageMedia(
                $messageId,
                [
                    "type" => "photo",
                    "media" => $file->getFile(),
                    "caption" => $text,
                ],
                $keyboard
            );

    }

    public function getFriendList(Request $request)
    {
        //Bot::query()->find(21);//;
        //BotUser::query()->find(182);//$request->botUser;

        $list = BusinessLogic::manager()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->friendList();

        return response()->json($list);
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
            "ref_code" => base64_encode("001" . $botUser->telegram_chat_id),
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

        $message = sprintf("Имя: %s
Телефон: %s
Город: %s
Колл-во слотов под ботов: %s
        ",
            $botUser->name ?? 'Не указано',
            $botUser->phone ?? 'Не указано',
            $botUser->city ?? 'Не указано',
            //$botUser->birthday ?? 'Не указано',
            // $botUser->manager->balance ?? 0,
            // $botUser->cashBack->amount ?? 0,
            //  $botUser->sex ? 'Мужской' : 'Женский',
            //  $botUser->manager->max_company_slot_count ?? 0,
            $botUser->manager->max_bot_slot_count ?? 0,
        );

        $companyDomain = $bot->company->slug;

        $path = env("APP_URL").$botUser->manager->image;//storage_path("app/public") . "/companies/$companyDomain/" . ($botUser->manager->image ?? 'noimage.jpg');

        Log::info($path);
        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/manager.png"
        );


        \App\Facades\BotManager::bot()
            ->replyPhoto("Кабинет менеджера к вашим услугам:\n$message",
                $file,

                [
                    [
                        ["text" => "👨🏽‍💻Детали профиля", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/manager-profile"
                        ]],
                    ],
                    [
                        ["text" => "💳Перейти в кабинет",
                            "login_url" => [
                                'url' => env("APP_URL")."/auth/tg-link"
                            ]
                        ],
                    ],

                ]);


    }

    public function managerHomePage(Request $request, $botDomain)
    {
        $request->validate([
            "slug" => "required"
        ]);

        $scriptId = $request->slug;

        $bot = \App\Models\Bot::query()
            ->with(["company", "imageMenus"])
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Inertia::setRootView("shop");
            return Inertia::render('Error');
        }

        if ($scriptId == "route") {
            Inertia::setRootView("bot");

            return Inertia::render('SimpleMain', [
                'bot' => BotSecurityResource::make($bot),
            ]);
        }

        $slug = BotMenuSlug::query()
            ->where("id", $scriptId)
            ->where("bot_id", $bot->id)
            // ->where("slug", self::SCRIPT)
            ->first();

        if (is_null($slug)) {
            Inertia::setRootView("shop");
            return Inertia::render('Error');
        }


        Inertia::setRootView("bot");

        return Inertia::render('SimpleMain', [
            'bot' => BotSecurityResource::make($bot),
            'slug_id' => $slug->id,
        ]);


    }
}
