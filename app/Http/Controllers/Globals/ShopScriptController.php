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

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_shop_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Магазин",
                'comment' => "Модуль магазина",
            ]);

        $params = [
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

        if (count($model->config ?? []) != count($params)) {
            $model->config = $params;
            $model->save();
        }

    }


    public function simpleHomePage(Request $request, $botDomain) {
        $request->validate([
            "slug" => "required"
        ]);

        $scriptId = $request->slug;

        $bot = \App\Models\Bot::query()
            ->with(["company", "imageMenus"])
            ->where("bot_domain", $botDomain)
            ->first();

        if (is_null($bot)) {
            Inertia::setRootView("bot");
            return Inertia::render('V1/Error');
        }

        if ($scriptId == "route") {
            Inertia::setRootView("bot");

            return Inertia::render('MainV2', [
                'bot' => BotSecurityResource::make($bot),
                'theme'=>$bot->settings["theme"] ?? null
            ]);
        }

        $slug = BotMenuSlug::query()
            ->where("id", $scriptId)
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($slug))
            $slug = BotMenuSlug::query()
                ->where("parent_slug_id", $scriptId)
                ->where("bot_id", $bot->id)
                ->first();

        if (is_null($slug)) {
            Inertia::setRootView("bot");
            return Inertia::render('V1/Error');
        }


        Inertia::setRootView("bot");

        return Inertia::render('MainV2', [
            'bot' => BotSecurityResource::make($bot),
            'slug_id' => $slug->id,
            'theme'=>$bot->settings["theme"] ?? null
        ]);

    }

    public function shopHomePage(Request $request, $botDomain)
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
            Inertia::setRootView("shop");

            return Inertia::render('MainV1', [
                'bot' => BotSecurityResource::make($bot),
            ]);
        }

        $slug = BotMenuSlug::query()
            ->where("id", $scriptId)
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($slug))
            $slug = BotMenuSlug::query()
                ->where("parent_slug_id", $scriptId)
                ->where("bot_id", $bot->id)
                ->first();

        if (is_null($slug)) {
            Inertia::setRootView("shop");
            return Inertia::render('V1/Error');
        }


        Inertia::setRootView("shop");

        return Inertia::render('MainV1', [
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
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#home"
                        ]],
                    ],

                ]);
    }
}
