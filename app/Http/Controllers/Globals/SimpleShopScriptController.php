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
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\Product;
use App\Models\ProductCategory;
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
                'command' => ".*Магазин товаров",
                'comment' => "Модуль вывода товаров в ТГ-бот, включая корзину и детали о товаре",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "products_per_page",
                "value" => 10,

            ],
            [
                "type" => "text",
                "key" => "shop_title",
                "value" => "Меню продукции",

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
                'command' => ".*Корзина .([0-9]{1,3}).",
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


    public function nextProductPage()
    {
        BotManager::bot()->reply("Следующая страница товаров");
    }

    public function detailProduct()
    {
        BotManager::bot()->reply("Детали товара");
    }

    public function orders(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        BotManager::bot()->reply("История заказов");
    }

    public function addToBasket()
    {
        BotManager::bot()->reply("Добавить в корзину");
    }

    public function productsInCategory(){
        BotManager::bot()->reply("Товары в категории");
    }

    public function basket(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        BotManager::bot()->reply("Корзина");
    }

    public function categories(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $categories = ProductCategory::query()
            ->where("bot_id", $bot->id)
            //->whereHas("products")
            //->take(5)
            ->get();

        $keyboard = [];
        foreach ($categories as $category) {
            $keyboard[] =
                [
                    ["text" => $category->title, "callback_data" => "/category_products $category->id"],
                ];
        }

        BotManager::bot()
            ->sendPhoto(
                $botUser->telegram_chat_id,
                "Категории товаров",
                InputFile::create($product->images[0] ?? public_path() . "/images/cashman-save-up.png"),
                $keyboard
              );
    }

    public function main(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $count = (Collection::make($config[1])
            ->where("key", "products_per_page")
            ->first())["value"] ?? 10;

        $title = (Collection::make($config[1])
            ->where("key", "shop_title")
            ->first())["value"] ?? "Меню";

        $products = Product::query()
            ->where("bot_id", $bot->id)
            ->take($count)
            ->skip(0)
            ->get();

        foreach ($products as $product) {
            BotManager::bot()
                ->sendPhoto(
                    $botUser->telegram_chat_id,
                    $product->title,
                    InputFile::create($product->images[0] ?? public_path() . "/images/cashman-save-up.png"),
                    [
                        [
                            ["text" => "👍Детали товара", "callback_data" => "/detail_global_product $product->id"],
                        ],
                        [
                            ["text" => "🛒Добавить в корзину", "callback_data" => "/detail_global_product $product->id"],
                        ],

                    ]);

        }

        $productInCart = 0;
        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'reply',
                    'slug' => "menu_products",

                ],
                [
                    'menu' => [
                        [
                            ["text" => "🛒Корзина ($productInCart)"],
                        ],
                        [
                            ["text" => "🥂Категории товаров"],
                        ],
                        [
                            ["text" => "🌭Магазин товаров"],
                        ],
                        [
                            ["text" => "🕖История покупок"],
                        ],
                        [
                            ["text" => "🔥Главное меню"],
                        ],
                    ],
                ]);

        \App\Facades\BotManager::bot()
            ->replyKeyboard(
                $title,
                $menu->menu);


    }
}
