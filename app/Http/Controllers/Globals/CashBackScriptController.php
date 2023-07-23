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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class CashBackScriptController extends SlugController
{
    const SCRIPT = "global_cashback_main";

    public function config(Bot $bot)
    {

        $slug = BotMenuSlug::query()
            ->where("slug", "global_cashback_budget")
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($slug))
            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Мой бюджет",
                'comment' => "Бюджет пользователя системой КэшБэк",
                'slug' => "global_cashback_budget",
                'is_global' => true,
            ]);

        $slug = BotMenuSlug::query()
            ->where("slug", "global_cashback_request")
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($slug))
            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Запросить CashBack",
                'comment' => "Механизм вызова администратора",
                'slug' => "global_cashback_request",
                'is_global' => true,
            ]);

    }

    public function myBudget(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $bot = BotManager::bot()->getSelf();

        $menu = BotMenuTemplate::query()
            ->where("slug", "menu_cashback_budget_$slugId")
            ->where('bot_id', $bot->id)
            ->where('type', 'reply')
            ->first();

        if (is_null($menu))
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => 'reply',
                'slug' => "menu_cashback_budget_$slugId",
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
            ->where("slug", "menu_cashback_request_$slugId")
            ->where('bot_id', $bot->id)
            ->where('type', 'reply')
            ->first();

        if (is_null($menu))
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => 'inline',
                'slug' => "menu_cashback_request_$slugId",
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
                                "url" => env("APP_URL") . "/restaurant/vip-form/$botDomain"
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
            ->where("slug", "menu_cashback_$slugId")
            ->where('bot_id', $bot->id)
            ->where('type', 'reply')
            ->first();

        if (is_null($menu))
            $menu = BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => 'reply',
                'slug' => "menu_cashback_$slugId",
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
