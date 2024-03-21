<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class CashBackScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                'slug' => "global_cashback_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Special CashBack System",
                'comment' => "Система КэшБэка",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "custom_profile_form_script_id",
                "value" => null
            ],
            [
                "type" => "text",
                "key" => "first_cashback_granted",
                "value" => 0
            ],
            [
                "type" => "boolean",
                "key" => "first_cashback_need_fail_message",
                "value" => false,

            ],

            [
                "type" => "text",
                "key" => "display_type",
                "value" => 0
            ],
            [
                "type" => "boolean",
                "key" => "need_birthday",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "need_city",
                "value" => true,
            ],
            [
                "type" => "boolean",
                "key" => "need_sex",
                "value" => true,
            ],
            [
                "type" => "boolean",
                "key" => "need_age",
                "value" => true,
            ],
            [
                "type" => "image",
                "key" => "image",
                "value" => null,
            ],

        ];

        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_cashback_budget",
                'parent_slug_id' => null,
                'bot_id' => null,
                'is_global' => true,
            ],
            [
                'command' => ".*Мой бюджет",
                'comment' => "Бюджет пользователя системой КэшБэк",
            ]);


        BotMenuSlug::query()->updateOrCreate(
            [

                'slug' => "global_cashback_request",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Запросить CashBack",
                'comment' => "Механизм вызова администратора",
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [


                'slug' => "global_cashback_write_offs",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Списания",
                'comment' => "Списания КэшБэка",
            ]);

        BotMenuSlug::query()->updateOrCreate(
            [

                'slug' => "global_cashback_charges",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Начисления",
                'comment' => "Начисления КэшБэка",
            ]);

        $model = BotMenuSlug::query()->updateOrCreate(
            [

                'parent_slug_id' => null,
                'bot_id' => null,
                'slug' => "global_cashback_book_table",
                'is_global' => true,
            ],
            [
                'command' => ".*Забронировать столик",
                'comment' => "Бронирование столика",
            ]);

        $params = [
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
        ];

        if (count($model->config ?? []) != count($params)) {
            $model->config = $params;
            $model->save();
        }

    }

    public function admins()
    {

        $bot = BotManager::bot()->getSelf();

        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [

                    'type' => 'inline',
                    'slug' => "menu_admins_list_route",

                ],
                [
                    'menu' => [
                        [
                            ["text" => "Пригласить администратора", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route#/admins"//"/restaurant/active-admins/$bot->bot_domain"
                            ]],
                        ],
                    ],
                ]);

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard("CashBack-запрос", $menu->menu);
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

                    'type' => 'inline',
                    'slug' => "menu_booking_table_$slugId",

                ],
                [
                    'menu' => [
                        [
                            ["text" => $btnText, "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/book-a-table"//"/restaurant/active-admins/$bot->bot_domain"
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
            ->first())["value"] ?? 'cashback';

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
                    "bot_id" => $bot->id,
                    'type' => 'inline',
                    'slug' => "menu_cashback_request_$slugId",

                ],
                [
                    'menu' => [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Пригласить администратора", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route#/admins"
                            ]],
                        ],
                    ],
                ]);

        BotManager::bot()
            ->replyInlineKeyboard("Меню вызова администратора", $menu->menu);


    }

    public function loadData(Request $request)
    {
        $slug = $request->slug;

        return response()->json(
            [
                'display_type' => (Collection::make($slug->config)
                        ->where("key", "display_type")
                        ->first())["value"] ?? 0,
                'need_birthday' => (Collection::make($slug->config)
                        ->where("key", "need_birthday")
                        ->first())["value"] ?? true,
                'need_age' => (Collection::make($slug->config)
                        ->where("key", "need_age")
                        ->first())["value"] ?? true,
                'need_city' => (Collection::make($slug->config)
                        ->where("key", "need_city")
                        ->first())["value"] ?? true,
                'need_sex' => (Collection::make($slug->config)
                        ->where("key", "need_sex")
                        ->first())["value"] ?? true,
            ]
        );
    }

    public function specialCashBackSystem(...$config)
    {

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $image = (Collection::make($config[1])
            ->where("key", "image")
            ->first())["value"] ?? null;

        $bot = BotManager::bot()->getSelf();

        $botDomain = $bot->bot_domain;

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_vip) {


            $bot = BotManager::bot()->getSelf();

            $scriptFormId = (Collection::make($config[1])
                ->where("key", "custom_profile_form_script_id")
                ->first())["value"] ?? null;


            if (!is_null($scriptFormId)) {
                BotManager::bot()
                    ->runSlug($scriptFormId);
            } else
                \App\Facades\BotManager::bot()
                    ->replyPhoto("Заполни эту анкету и получи доступ к системе CashBack",
                        InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                        [
                            [
                                ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/vip"
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
            ->with(["subs"])
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->first();

        if (is_null($cashBack->bot_user_id)) {
            $cashBack->bot_user_id = $botUser->id;
            $cashBack->save();
        }

        $amount = is_null($cashBack) ? 0 : ($cashBack->amount ?? 0);

        $companyTitle = $bot->company->title ?? 'CashMan';

        $tmpSubsText = "";

        if (!is_null($botUser->cashBack->subs ?? null)) {

            if (count($botUser->cashBack->subs) > 0) {

                $tmpSubsText = "У вас есть специальные начисления:\n";
                foreach ($botUser->cashBack->subs as $sub) {
                    $tmpSubsText .= $sub->title . " <b>" . $sub->amount . " руб.</b>\n";
                }
            }

        }

        $percent = $bot->cashback_fire_percent ?? 0;
        $period = $bot->cashback_fire_period ?? 0;

        $tmpFiredText = "";

        if ($period != 0 && $percent == 0) {
            $nextFired = Carbon::parse(Carbon::parse($cashBack->fired_at)
                    ->addDays($period ?? 0)->timestamp - Carbon::now()->timestamp)
                ->format("Y-m-D H:i:s");

            $tmpFiredText = "<strong>Внимание!</strong>$percent % CashBack сгорает каждые $period дней! Успей потратить!Следующее сгорание $nextFired";
        }

        \App\Facades\BotManager::bot()
            ->reply("У вас <b>$amount</b> руб.!\n$tmpSubsText $tmpFiredText
Для начисления CashBack при оплате за услуги дайте отсканировать данный QR-код сотруднику <b>$companyTitle</b>\nhttps://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr");

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [

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
