<?php

namespace App\Http\Controllers\Globals;

use App\Classes\BotMethods;
use App\Classes\SlugController;
use App\Enums\OrderStatusEnum;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\ShopConfigPublicResource;
use App\Models\ActionStatus;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class FastoranController extends SlugController
{
    public function config(Bot $bot)
    {


        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_fastoran_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],

            [
                'command' => ".*Макретплейс",
                'comment' => "Скрипт Fastran",
            ]);

        $mainScript->config = [];
        $mainScript->save();

    }

    public function shopList(Request $request){
        $bots = Bot::query()
            ->take(10)
           // ->where('config->can_work_in_marketplace', false)
            ->get();

        $botIds = array_values($bots->pluck("id")->toArray());

        $categories = ProductCategory::query()
            ->with(["products"])
            ->whereIn("bot_id", $botIds)
            ->get();

        return $categories;
    }

    public function shopCategoryList(Request $request){
        $bots = Bot::query()
            ->take(10)
            // ->where('config->can_work_in_marketplace', false)
            ->get();

        $botIds = array_values($bots->pluck("id")->toArray());

        $categories = ProductCategory::query()
            ->with(["products"])
            ->whereIn("bot_id", $botIds)
            ->select("")
            ->get();

        return $categories;
    }

    public function shopProductList(Request $request){
        $bots = Bot::query()
            ->take(10)
            // ->where('config->can_work_in_marketplace', false)
            ->get();

        $botIds = array_values($bots->pluck("id")->toArray());

        $categories = Product::query()
            ->with(["products"])
            ->whereIn("bot_id", $botIds)
            ->select("")
            ->get();

        return $categories;
    }


    public function fastoranScript(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Сервис доставки";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Открыть";

        $mainImage = (Collection::make($config[1])
            ->where("key", "main_image")
            ->first())["value"] ?? null;


        $keyboard = [
            [
                ["text" => $btnText, "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=route#/s/menu"]
                ],
            ],

        ];

        \App\Facades\BotManager::bot()
            ->replyPhoto("$mainText", $mainImage, $keyboard);

    }
}
