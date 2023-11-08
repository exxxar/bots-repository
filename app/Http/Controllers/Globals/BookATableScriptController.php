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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class BookATableScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $mainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_cashback_book_table")
            ->first();

        if (is_null($mainScript))
            return;



        $model = BotMenuSlug::query()->updateOrCreate(
            [
                'bot_id' => $bot->id,
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
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/book-a-table"//"/restaurant/active-admins/$bot->bot_domain"
                            ]],
                        ],
                    ],
                ]);

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard($bookTableMessage, $menu->menu);
    }


}
