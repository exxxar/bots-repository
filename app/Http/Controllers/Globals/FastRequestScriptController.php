<?php

namespace App\Http\Controllers\Globals;

use App\Classes\BotMethods;
use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class FastRequestScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_fast_request_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Быстрый запрос",
                'comment' => "быстрый запрос к администрации бота",
            ]);

        $params = [

            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Запросить обратную связь",

            ],
            [
                "type" => "text",
                "key" => "pre_text",
                "value" => "Если заинтересовало, жми!",

            ],
            [
                "type" => "text",
                "key" => "result_message",
                "value" => "%s, наш менеджер свяжется с вами в ближайшее время!",

            ],

        ];
        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }

    }

    public function requestCallback(...$data)
    {
        $slugId = $data[3] ?? null;
        $parentPageId = $data[4] ?? null;

        Log::info("requestCallback");
        Log::info("slug $slugId");
        Log::info("page $parentPageId");

        $slug = BotMenuSlug::query()
            ->with(["page"])
            ->where("id", $slugId)
            ->first();

        $page = BotPage::query()
              ->with(["slug"])
              ->where("id", $parentPageId)
              ->first();


        if (is_null($slug)) {
            BotManager::bot()->reply("Упс... у нас тут заминочка!");
            return;
        }

        $botUser = BotManager::bot()->currentBotUser();

        $name = \App\Facades\BotMethods::prepareUserName($botUser);

        $resultText = (Collection::make($slug->config)
            ->where("key", "result_message")
            ->first())["value"] ?? "%s, Ваш запрос получен!";

        $bot = BotManager::bot()->getSelf();

        $from = $page->slug->command ?? 'Источник запроса не указан';
        $sex = $botUser->sex ? "Мужской" : "Женский";
        $phone = $botUser->phone ?? 'Не указан';
        $city = $botUser->city ?? 'Не указан';
        $birth = $botUser->birthday ?? 'Не указан';
        $age = $botUser->age ?? 'Не указан';

        $thread = $bot->topics["questions"] ?? null;

        BotManager::bot()
            ->sendMessage(($bot->order_channel ?? $bot->main_channel ?? null),
                "Быстрый запрос из $from\nот пользователя $name:\nПол:$sex\nТелефон:$phone\nГород:$city\nДР:$birth (возраст $age)",
                $thread

            );

        BotManager::bot()->reply(sprintf($resultText, $name));
    }

    public function fastRequest(...$config)
    {

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"] ?? null;

        $parentPageId = (Collection::make($config[1])
            ->where("key", "parent_page_id")
            ->first())["value"] ?? null;


        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "Запросить";


        /* $resultText = (Collection::make($config[1])
             ->where("key", "result_message")
             ->first())["value"] ?? "%s, Ваш запрос получен!";*/

        $preText = (Collection::make($config[1])
            ->where("key", "pre_text")
            ->first())["value"] ?? "Текст";

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_vip) {
            $bot = BotManager::bot()->getSelf();

            \App\Facades\BotManager::bot()
                ->replyPhoto("Для начала необходимо заполнить анкету!",
                    InputFile::create(public_path() . "/images/cashman2.jpg"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/vip"
                            ]],
                        ],

                    ]);

            return;
        }


        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'inline',
                    'slug' => "menu_fast_request_$slugId",

                ], [
                'menu' => [
                    [
                        ["text" => "$btnText", "callback_data" => "/request_callback $slugId $parentPageId"],
                    ],
                ],
            ]);

        Log::info("/request_callback slug=$slugId page=$parentPageId");

        BotManager::bot()
            ->replyInlineKeyboard("$preText", $menu->menu);

    }
}
