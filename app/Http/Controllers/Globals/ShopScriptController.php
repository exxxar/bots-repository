<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\BotMenuSlug;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class ShopScriptController extends Controller
{
    const SCRIPT = "global_shop_main";

/*    const KEY_MAX_ATTEMPTS = "max_attempts";
    const KEY_CALLBACK_CHANNEL_ID = "callback_channel_id";
    const KEY_RULES_TEXT = "rules_text";
    const KEY_RESULT_MESSAGE = "result_message";*/
    const KEY_MAIN_TEXT = "main_text";
    const KEY_BTN_TEXT = "btn_text";

    public function shopProducts($botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();


        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", self::SCRIPT)
            ->orderBy("updated_at", "desc")
            ->first();

        Inertia::setRootView("shop");

        return Inertia::render('Shop/Products', [
            'bot' => json_decode($bot->toJson()),
        ]);

    }

    public function shopHomepage($botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();


        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", self::SCRIPT)
            ->orderBy("updated_at", "desc")
            ->first();

        Inertia::setRootView("shop");

        return Inertia::render('Shop/Home', [
            'bot' => json_decode($bot->toJson()),
        ]);

    }

    public function shopMain(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", self::KEY_MAIN_TEXT)
            ->first())["value"] ?? "Покупай товары в нашем сервисе";

        $btnText = (Collection::make($config[1])
            ->where("key", self::KEY_BTN_TEXT)
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Перейти в магазин";

        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/shopify.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/global-scripts/shop/home/$bot->bot_domain"
                        ]],
                    ],

                ]);
    }
}
