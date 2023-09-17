<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use ReflectionClass;
use Telegram\Bot\FileUpload\InputFile;

class SimpleShopScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_simple_shop")
            ->first();


        if (is_null($hasMainScript))
            return;

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_simple_shop",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Упрощенный интернет-магазин",
                'comment' => "Модуль вывода товаров в ТГ-бот, включая корзину и детали о товаре",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "products_per_page",
                "value" => 10,

            ],
            [
                "type" => "channel",
                "key" => "callback_channel_id",
                "value" => $bot->order_channel ?? $bot->main_channel ?? env("BASE_ADMIN_CHANNEL"),
            ],

        ];


        if (count($model->config ?? []) < count($params)) {
            $model->config = $params;
            $model->save();
        }

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_products_categories",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Категории товаров",
                'comment' => "Скрипт отображения категорий товаров",
            ]);

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_product_basket",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Корзина товаров",
                'comment' => "Скрипт отображения корзины товаров",
            ]);

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_order_history",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*История покупок",
                'comment' => "Скрипт отображения истории покупок товаров",
            ]);

    }



    public function nextProductPage(){
        BotManager::bot()->reply("Следующая страница товаров");
    }

    public function detailProduct(){
        BotManager::bot()->reply("Детали товара");
    }

    public function orders(...$config){
        $bot = BotManager::bot()->getSelf();

        BotManager::bot()->reply("История заказов");
    }

    public function basket(...$config){
        $bot = BotManager::bot()->getSelf();

        BotManager::bot()->reply("Корзина");
    }

    public function categories(...$config){
        $bot = BotManager::bot()->getSelf();

        BotManager::bot()->reply("Категории товара");
    }

    public function main(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $count = (Collection::make($config[1])
            ->where("key", "products_per_page")
            ->first())["value"] ?? 10;

        $products = Product::query()
            ->where("bot_id", $bot->id)
            ->take($count)
            ->skip(0)
            ->get();

        foreach ($products as $product){
            BotManager::bot()
                ->sendPhoto(
                    $botUser->telegram_chat_id,
                    $product->title,
                    InputFile::create($product->images[0] ?? public_path() . "/images/cashman-save-up.png"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "callback_data" =>"/detail_global_product $bot->id $product->id"],
                        ],

                    ]);

        }
    }
}
