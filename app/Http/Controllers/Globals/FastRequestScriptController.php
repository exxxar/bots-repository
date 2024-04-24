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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use stdClass;
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
                "type" => "image",
                "key" => "main_image",
                "value" => null,

            ],
            [
                "type" => "script",
                "key" => "profile_id",
                "value" => null,

            ],
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
                "type" => "text",
                "key" => "result_message",
                "value" => "%s, наш менеджер свяжется с вами в ближайшее время!",

            ],

        ];

        $mainScript->config = $params;
        $mainScript->save();


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

    public function requestCallback(...$data)
    {
        $slugId = $data[3] ?? null;
        $parentPageId = $data[4] ?? null;


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

        Log::info("TEST FAST REQUEST 1");
        $from = !is_null($page) ? $page->slug->command ?? 'Источник запроса не указан' : 'Источник запроса не указан';
        $sex = $botUser->sex ? "Мужской" : "Женский";
        $phone = $botUser->phone ?? 'Не указан';
        $city = $botUser->city ?? 'Не указан';
        $birth = $botUser->birthday ?? 'Не указан';
        $age = $botUser->age ?? 'Не указан';

        $thread = $bot->topics["questions"] ?? null;

        BotManager::bot()
            ->sendMessage(($bot->order_channel ?? null),
                "Быстрый запрос из $from\nот пользователя $name:\nПол:$sex\nТелефон:$phone\nГород:$city\nДР:$birth (возраст $age)",
                $thread

            );
        Log::info("TEST FAST REQUEST 2");
        try {
            BotManager::bot()->reply(sprintf($resultText, $name));
        } catch (\Exception $e) {
            BotManager::bot()->reply("Спасибо! Ваш запрос отправлен!");
        }

        Log::info("TEST FAST REQUEST 3");
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


        $mainImage = (Collection::make($config[1])
            ->where("key", "main_image")
            ->first())["value"] ?? null;

        $profileScriptId = (Collection::make($config[1])
            ->where("key", "profile_id")
            ->first())["value"] ?? null;

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

            if (!is_null($profileScriptId) && $profileScriptId instanceof stdClass == "integer") {


                $isRun = BotManager::bot()
                    ->runPage($profileScriptId, $bot, $botUser) ?? false;

                if (!$isRun) {
                    $isRun = BotManager::bot()
                        ->runSlug($profileScriptId, $bot, $botUser) ?? false;
                }


            } else {
                $keyboard = [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/vip"
                        ]],
                    ],

                ];


                if (is_null($mainImage))
                    \App\Facades\BotManager::bot()
                        ->replyInlineKeyboard("Для начала необходимо заполнить анкету!", $keyboard);
                else
                    \App\Facades\BotManager::bot()
                        ->replyPhoto("Для начала необходимо заполнить анкету!", $mainImage, $keyboard);

            }

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
                            ["text" => "$btnText", "callback_data" => is_null($parentPageId) ?
                                "/service_request_callback_without_page $slugId" :
                                "/service_request_callback $slugId $parentPageId"
                            ],
                        ],
                    ],
                ]);

            if (is_null($mainImage)) {

                \App\Facades\BotManager::bot()
                    ->replyInlineKeyboard("$preText", $menu->menu);
            } else
            {

                \App\Facades\BotManager::bot()
                    ->replyPhoto("$preText", $mainImage, $menu->menu);
            }




    }
}
