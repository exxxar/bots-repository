<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class ShopScriptController extends Controller
{
    const SCRIPT = "global_shop_main";

    const KEY_SHOP_TITLE = "shop_title";
    const KEY_OWNER_ID = "owner_id"; //-197151608

/*    const KEY_MAX_ATTEMPTS = "max_attempts";
    const KEY_CALLBACK_CHANNEL_ID = "callback_channel_id";
    const KEY_RULES_TEXT = "rules_text";
    const KEY_RESULT_MESSAGE = "result_message";*/
    const KEY_MAIN_TEXT = "main_text";
    const KEY_BTN_TEXT = "btn_text";


    public function shopHomePage(Request $request, $scriptId, $botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->with(["company", "imageMenus"])
            ->where("bot_domain", $botDomain)
            ->first();


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

    public function shopAdminPage( $botDomain)
    {
        $bot = Bot::query()
            ->with(["company","imageMenus"])
            ->where("bot_domain", $botDomain)
            ->first();


        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", self::SCRIPT)
            ->orderBy("updated_at", "desc")
            ->first();

        Inertia::setRootView("bot");


        return Inertia::render('Shop/Admin/Main', [
            'bot' =>BotSecurityResource::make($bot),
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

    public function shopAdmin(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_admin){
            \App\Facades\BotManager::bot()
                ->reply("К сожалению, вы не являетесь администратором данного бота!");
            return;
        }


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
                            "url" => env("APP_URL") . "/global-scripts/shop/admin/$bot->bot_domain"
                        ]],
                    ],

                ]);
    }
}
