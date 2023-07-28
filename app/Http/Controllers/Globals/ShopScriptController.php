<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class ShopScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_shop_main")
            ->first();


        if (is_null($hasMainScript))
            return;

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_shop_main",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Магазин",
                'comment' => "Модуль магазина",
            ]);

        if (empty($model->config ?? [])) {
            $model->config = [
                [
                    "type" => "text",
                    "key" => "main_text",
                    "value" => "Наш магазин товаров",

                ],

                [
                    "type" => "text",
                    "key" => "btn_text",
                    "value" => "В магазин",

                ],

            ];
            $model->save();
        }

    }


    public function shopTestCallback(Request $request, $botDomain)
    {


      Log::info("success auth checked");
      Log::info("1".(   print_r($request->botUser ?? 'нет юзера',true)));
      Log::info("2".(   print_r($request->bot ?? 'нет бота',true) ));


    }

    public function shopHomePage(Request $request, $scriptId, $botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->with(["company", "imageMenus"])
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Inertia::setRootView("bot");
            return Inertia::render('Error');
        }

        if ($scriptId == "route") {
            Inertia::setRootView("shop");

            return Inertia::render('Shop/Main', [
                'bot' => BotSecurityResource::make($bot),
            ]);
        }

        $slug = BotMenuSlug::query()
            ->where("id", $scriptId)
            ->where("bot_id", $bot->id)
            // ->where("slug", self::SCRIPT)
            ->first();

        if (is_null($slug)) {
            Inertia::setRootView("bot");
            return Inertia::render('Error');
        }


        Inertia::setRootView("shop");

        return Inertia::render('Shop/Main', [
            'bot' => BotSecurityResource::make($bot),
            'slug_id' => $slug->id,
        ]);


    }

    public function shopMain(...$config)
    {
        $bot = BotManager::bot()->getSelf();


        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Покупай товары в нашем сервисе";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Перейти в магазин";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/shopify.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/global-scripts/$slugId/interface/$bot->bot_domain#home"
                        ]],
                    ],

                ]);
    }
}
