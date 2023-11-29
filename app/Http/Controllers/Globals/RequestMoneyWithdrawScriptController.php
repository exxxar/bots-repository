<?php

namespace App\Http\Controllers\Globals;

use App\Classes\BotMethods;
use App\Classes\SlugController;
use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\ActionStatus;
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

class RequestMoneyWithdrawScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $mainScript = BotMenuSlug::query()
            ->whereNull("parent_slug_id")
            ->whereNull("bot_id")
            ->where("slug", "global_cash_out_main")
            ->first();

        if (is_null($mainScript))
            return;

        BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_cash_out_main",
                'is_global' => true,
            ],
            [
                'command' => ".*Вывод средств",
                'comment' => "Скрипт вывода средств на карту",
            ]);

        $params = [

            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Вывод средств",

            ],

            [
                "type" => "text",
                "key" => "min_cash_out_value",
                "value" => 500,

            ],

            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Вывод средств",

            ],

        ];

        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }



    }

    public function withDrawMoney(Request $request)
    {

        $request->validate([
            "amount" => "required",
            "card" => "required",
            "info" => "required",
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $minCashOutValue = (Collection::make($slug->config)
            ->where("key", "min_cash_out_value")
            ->first())["value"] ?? 500;

        Log::info("cashback=>".print_r($botUser->cashBack, true));
        $cashBackAmount = $botUser->cashBack->amount ?? 0;

        if ($cashBackAmount < $minCashOutValue) {
            \App\Facades\BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage($botUser->telegram_chat_id,
                    "У вас на баласне <b>$cashBackAmount руб.</b>. Вывод доступен от $minCashOutValue руб.");

            return response()->noContent(400);
        }

        $amount = $request->amount ?? 0;
        $info = $request->info ?? null;
        $card = $request->card ?? 'не указана';

        $adminBotUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("is_admin", true)
            ->first();

        $callbackChannel = $bot->order_channel ?? $bot->main_channel ?? null;
        $name = \App\Facades\BotMethods::prepareUserName($botUser);
        if (is_null($callbackChannel) || is_null($adminBotUser)) {
            \App\Facades\BotMethods::bot()
                ->whereDomain($bot->bot_domain)
                ->sendMessage($botUser
                    ->telegram_chat_id,
                    "Рассмотрение заявок на вывод средств временно недоступно. Приносим свои извенения за временные неудобства!");
            return;
        }

        event(new CashBackEvent(
            (int)$bot->id,
            (int)$botUser->user_id,
            (int)$adminBotUser->user_id,
            ((float)$amount),
            "Вывод средств: " . ($info ?? ""),
            CashBackDirectionEnum::Debiting
        ));

        $data = "001" . $botUser->telegram_chat_id;

        $link = "https://t.me/$bot->bot_domain?start=" .
            base64_encode($data);

        $thread = $bot->topics["ask-money"] ?? null;

        \App\Facades\BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($botUser
                ->telegram_chat_id,
                "$name, вы запросили вывод средств в размере $amount руб на карту #$card! Данная операция выполняется в течении 48 часов. Для вопроса оператору напишите текст в бота длинее 10 символов.")
            ->sendInlineKeyboard($callbackChannel,
                "#выводсредств\nПользователь $name запросил вывод средств в размере $amount руб (из $cashBackAmount руб. на балансе) на карту #$card. Комментарий к заявке:\n" . ($info ?? 'Без комментария')."\nСредства в размере $amount руб. автоматически списались со счета пользователя $name.", [
                    [
                        ["text" => "Работа с пользователем", "url" => $link]
                    ]
                ], $thread);

        return response()->noContent();
    }

    public function moneyWithdrawScript(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $minCashOutValue = (Collection::make($config[1])
            ->where("key", "min_cash_out_value")
            ->first())["value"] ?? 500;

        $cashBackAmount = $botUser->cashBack->amount ?? 0;

        Log::info("cashback=>".print_r($botUser->cashBack, true));

        \App\Facades\BotManager::bot()
            ->replyPhoto(($cashBackAmount < $minCashOutValue ?
                "Форма вывода средств: у вас на баласне <b>$cashBackAmount руб.</b>. Вывод доступен от $minCashOutValue руб." :
                "Форма вывода средств: доступно к выводу <b>$cashBackAmount руб.</b>"),
                InputFile::create($image ?? public_path() . "/images/cashman-cashout.png"),
                $cashBackAmount < $minCashOutValue ? [] :
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Вывести средства", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/cash-out"
                            ]],
                        ],

                    ]);


    }
}
