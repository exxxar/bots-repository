<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class CashBackScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug","global_cashback_main")
            ->first();

        if (is_null($hasMainScript))
            return;

        BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_cashback_budget",
                "bot_id" => $bot->id,
                'is_global' => true,
                'command' => ".*Мой бюджет",
                'comment' => "Бюджет пользователя системой КэшБэк",
            ],
            [
                'config' => null,
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [
                'bot_id' => $bot->id,
                'command' => ".*Запросить CashBack",
                'comment' => "Механизм вызова администратора",
                'slug' => "global_cashback_request",
                'is_global' => true,
            ],
            [
                'config' => null,
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [
                'bot_id' => $bot->id,
                'command' => ".*Списания",
                'comment' => "Списания КэшБэка",
                'slug' => "global_cashback_write_offs",
                'is_global' => true,
            ],
            [
                'config' => null,
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [
                'bot_id' => $bot->id,
                'command' => ".*Начисления",
                'comment' => "Начисления КэшБэка",
                'slug' => "global_cashback_charges",
                'is_global' => true,
            ],
            [
                'config' => null,
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [
                'bot_id' => $bot->id,
                'command' => ".*Забронировать столик",
                'comment' => "Бронирование столика",
                'slug' => "global_cashback_book_table",
                'is_global' => true,
            ],
            [
                'config' => [
                    [
                        "type" => "text",
                        "key" => "book_table_message",
                        "value" => "В открывшемся окне укажите какой именно столик вы хотите забронировать. Администратор заведения в телефонном режиме уточнит у вас информацию."
                    ],
                    [
                        "type" => "text",
                        "key" => "btn_text",
                        "value" => "\xF0\x9F\x8E\xB2Выбрать столик для бронирования",

                    ]
                ],
            ]);

    }

    public function bookTable(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];


        $bookTableMessage = (Collection::make($config[1])
            ->where("key", "book_table_message")
            ->first())["value"] ?? "Забронировать столик";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "Выбрать столик";

        $bot = BotManager::bot()->getSelf();

        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'inline',
                    'slug' => "menu_booking_table_$slugId",

                ],
                [
                    'menu' => [
                        [
                            ["text" => $btnText, "web_app" => [
                                "url" => env("APP_URL") . "/global-scripts/$slugId/interface/$bot->bot_domain#/book-a-table"//"/restaurant/active-admins/$bot->bot_domain"
                            ]],
                        ],
                    ],
                ]);

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard($bookTableMessage, $menu->menu);
    }

    public function charges(...$config)
    {
        $botUser = BotManager::bot()->currentBotUser();

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $botUser->bot_id)
            ->where("user_id", $botUser->user_id)
            ->where("operation_type", 1);

        $tmpCount = $cashBackHistories->count();

        $cashBackHistories = $cashBackHistories
            ->take(10)
            ->skip(0)
            ->get();

        $tmp = "<b>Начисления ($tmpCount операций):</b>\n";

        foreach ($cashBackHistories as $item) {
            $tmp .= "<b>" . $item->amount . "</b> руб уровень <em>" .
                ($item->level ?? 1) . "</em> " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";
        }

        if ($tmpCount > 10)
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($tmp, [
                    [
                        ["text" => "Загрузить еще", "callback_data" => "/more_cashback $botUser->bot_id $botUser->user_id 1 1"]
                    ]
                ]);
        else
            \App\Facades\BotManager::bot()
                ->reply($tmp);
    }

    public function writeOffs(...$config)
    {
        $botUser = BotManager::bot()->currentBotUser();

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $botUser->bot_id)
            ->where("user_id", $botUser->user_id)
            ->where("operation_type", 0);

        $tmpCount = $cashBackHistories->count();

        $cashBackHistories = $cashBackHistories
            ->take(10)
            ->skip(0)
            ->get();

        $tmp = "<b>Списания ($tmpCount операций):</b>\n";

        foreach ($cashBackHistories as $item) {
            $tmp .= "<b>" . $item->amount . "</b> руб " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";
        }

        if ($tmpCount > 10)
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($tmp, [
                    [
                        ["text" => "Загрузить еще", "callback_data" => "/more_cashback $botUser->bot_id $botUser->user_id 0 1"]
                    ]
                ]);
        else
            \App\Facades\BotManager::bot()
                ->reply($tmp);
    }

    public function myBudget(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $bot = BotManager::bot()->getSelf();


        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'reply',
                    'slug' => "menu_cashback_budget_$slugId",

                ],
                [
                    'menu' => [
                        [
                            ["text" => "\xF0\x9F\x93\x8DНачисления"],
                            ["text" => "\xF0\x9F\x93\x8DСписания"],
                        ],
                        [
                            ["text" => "\xF0\x9F\x93\x8DSpecial CashBack System"],
                        ],
                        [
                            ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                        ],
                    ],
                ]);

        BotManager::bot()
            ->replyKeyboard("Операции над вашим бюджетом", $menu->menu);

    }

    public function requestCashBack(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $bot = BotManager::bot()->getSelf();

        $botUsers = BotUser::query()
            ->where("is_admin", true)
            ->where("is_work", true)
            ->where("bot_id", $bot->id)
            ->get();

        if (count($botUsers) == 0) {
            BotManager::bot()->reply("К сожалению в данный момент нет доступных администраторов!");
            return;
        }

        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'inline',
                    'slug' => "menu_cashback_request_$slugId",

                ], [
                'menu' => [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Пригласить администратора", "web_app" => [
                            "url" => env("APP_URL") . "/restaurant/active-admins/$bot->bot_domain"
                        ]],
                    ],
                ],
            ]);

        BotManager::bot()
            ->replyInlineKeyboard("Меню вызова администратора", $menu->menu);
    }

    public function specialCashBackSystem(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $bot = BotManager::bot()->getSelf();

        $botDomain = $bot->bot_domain;

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_vip) {
            $bot = BotManager::bot()->getSelf();

            \App\Facades\BotManager::bot()
                ->replyPhoto("Заполни эту анкету и получит достук к системе CashBack",
                    InputFile::create(public_path() . "/images/cashman2.jpg"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                "url" => env("APP_URL") . "/global-scripts/$slugId/interface/$bot->bot_domain#/vip"//"/restaurant/active-admins/$bot->bot_domain"

                            ]],
                        ],

                    ]);

            return;
        }

        $botUser = BotManager::bot()->currentBotUser();

        $data = "001" . $botUser->telegram_chat_id;

        $qr = "https://t.me/$botDomain?start=" .
            base64_encode($data);


        $cashBack = CashBack::query()
            ->where("bot_id", $bot->id)
            ->where("user_id", $botUser->user_id)
            ->first();

        $amount = is_null($cashBack) ? 0 : ($cashBack->amount ?? 0);

        $companyTitle = $bot->company->title ?? 'CashMan';

        \App\Facades\BotManager::bot()
            ->replyPhoto("У вас <b>$amount</b> руб.!\n
Для начисления CashBack при оплате за услуги дайте отсканировать данный QR-код сотруднику <b>$companyTitle</b>",
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'reply',
                    'slug' => "menu_cashback_$slugId",

                ], [
                'menu' => [
                    [
                        ["text" => "\xF0\x9F\x93\x8DМой бюджет"],
                    ],
                    [
                        ["text" => "\xF0\x9F\x93\x8DЗапросить CashBack"],
                    ],
                    [
                        ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                    ],
                ],
            ]);

        BotManager::bot()
            ->replyKeyboard("Меню управления CashBack-ом", $menu->menu);

    }
}
