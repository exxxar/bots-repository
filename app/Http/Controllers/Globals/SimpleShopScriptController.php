<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
                "slug" => "global_clear_basket",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Очистить корзину",
                'comment' => "Скрипт очистки товаров в корзине",
            ]);


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
                "type" => "text",
                "key" => "shop_welcome_message",
                "value" => "Приветствуем вас, %s, в нашем магазине товаров!",

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




        $params = [
            [
                "type" => "text",
                "key" => "categories_per_page",
                "value" => 5,

            ],
        ];

        if (count($model->config ?? []) < count($params)) {
            $model->config = $params;
            $model->save();
        }

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_products_menu",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Наши товары",
                'comment' => "Скрипт отображения списка товаров",
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
                "slug" => "global_products_in_basket",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Товары в корзине",
                'comment' => "Вывод списка товаров в корзине",
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

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_start_order",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Оформление заказа",
                'comment' => "Вывод диалога оформления заказа",
            ]);

    }

    private function productsPage($page = 0, $count = 5, $categoryId = null)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $request = Product::query()
            ->where("bot_id", $bot->id);
        //->where("in_stop_list_at", false);

        if (!is_null($categoryId))
            $request = $request->whereHas("productCategories", function ($q) use ($categoryId) {
                $q->where("product_category_id", $categoryId);
            });


        $products = $request
            ->skip($page * $count)
            ->take($count)
            ->get();


        if (count($products) == 0) {
            BotManager::bot()
                ->reply("Упс... Товара то нет:(");
            return;
        }

        foreach ($products as $product) {

            $basket = Basket::query()
                ->where("product_id", $product->id)
                ->where("bot_id", $bot->id)
                ->where("bot_user_id", $botUser->id)
                ->whereNull("ordered_at")
                ->first();

            if (is_null($basket))

                $keyboard = [
                    [
                        ["text" => "💡Информация о товаре", "callback_data" => "/detail_global_product $product->id"],
                    ],
                    [

                        ["text" => "🛒Добавить в корзину $product->current_price ₽", "callback_data" => "/add_to_basket $product->id"],
                    ],
                ];
            else
                $keyboard = [
                    [
                        ["text" => "💡Информация о товаре", "callback_data" => "/detail_global_product $product->id"],
                    ],
                    [
                        ["text" => "🛒Добавить еще в корзину $product->current_price ₽", "callback_data" => "/add_to_basket $product->id"],
                    ],
                    [
                        ["text" => "👎Удалить из корзины", "callback_data" => "/remove_from_basket $product->id"],
                    ],
                ];

            BotManager::bot()
                ->sendPhoto(
                    $botUser->telegram_chat_id,
                    $product->title,
                    InputFile::create($product->images[0] ?? public_path() . "/images/cashman-save-up.png"),
                    $keyboard);

        }

        if (count($products) >= $count)
            BotManager::bot()
                ->replyInlineKeyboard("Текущая страница <b>" . ($page + 1) . "</b>",
                    [
                        [
                            ["text" => "👉Загрузить еще", "callback_data" =>
                                is_null($categoryId) ? "/next_global_products " . ($page + 1) : "/category_products $categoryId " . ($page + 1)
                            ],
                        ],

                    ]);
    }

    private function categoriesPage($page = 0, $count = 5)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $request = ProductCategory::query()
            ->where("bot_id", $bot->id)
            ->whereHas("products");


        $categories = $request
            ->skip($page * $count)
            ->take($count)
            ->get();

        if (count($categories) == 0) {
            BotManager::bot()
                ->reply("Упс... Категорий то нет:(");
            return;
        }


        $keyboard = [];
        foreach ($categories as $category) {
            $keyboard[] =
                [
                    ["text" => "$category->title ($category->count шт.)", "callback_data" => "/category_products $category->id 0"],
                ];
        }

        if (count($categories) >= $count)
            $keyboard[] = [
                ["text" => "👉Загрузить еще", "callback_data" => "/next_category_products " . ($page + 1)],
            ];

        BotManager::bot()
            ->sendPhoto(
                $botUser->telegram_chat_id,
                "Категории товаров, страница <b>" . ($page + 1) . "</b>",
                InputFile::create($product->images[0] ?? public_path() . "/images/cashman-save-up.png"),
                $keyboard
            );


    }

    public function nextProductPage(...$data)
    {
        $page = $data[3] ?? 0;
        $this->productsPage($page);
    }

    public function nextCategories(...$data)
    {
        $page = $data[3] ?? 0;
        $this->categoriesPage($page);
    }

    public function detailProduct(...$data)
    {
        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();




        $productId = $data[3] ?? null;


        $product = Product::query()
            ->where("id", $productId)
            ->first();

        if (is_null($product)) {
            BotManager::bot()->reply("Упс... Продукт не найден");
            return;
        }

        if (count($product->images) > 1) {
            $media = [];

            foreach ($product->images as $image) {

                $image = !str_contains($image, "http") ? env("APP_URL") . "/images/" . $bot->company->slug . "/" . $image : $image;

                $media[] = [
                    "media" => $image,
                    "type" => "photo",
                    "caption" => $image
                ];
            }

            BotManager::bot()->replyMediaGroup($media);

        } else if (count($product->images) === 1) {

            $image = $product->images[0];

            $image = !str_contains($image, "http") ? env("APP_URL") . "/images/" . $bot->company->slug . "/" . $image : $image;

            BotManager::bot()->replyPhoto("Изображение к товару",
                InputFile::create($image));
        }

        $basket = Basket::query()
            ->where("product_id", $productId)
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->whereNull("ordered_at")
            ->first();



        if (is_null($basket))

            $keyboard = [
                [
                    ["text" => "🛒Добавить в корзину $product->current_price ₽", "callback_data" => "/add_to_basket $product->id"],
                ],
            ];
        else
            $keyboard = [
                [
                    ["text" => "🛒Добавить еще в корзину $product->current_price ₽", "callback_data" => "/add_to_basket $product->id"],
                ],
                [
                    ["text" => "👎Удалить из корзины", "callback_data" => "/remove_from_basket $product->id"],
                ],
            ];




        BotManager::bot()
            ->replyInlineKeyboard("<b>$product->title</b>\n" .
                "$product->description\n" .
                "Старая цена: $product->old_price ₽\n" .
                "Цена товара: $product->current_price ₽",
                $keyboard
            );


    }

    public function orders(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        BotManager::bot()->reply("История заказов");
    }

    public function clearBasket(){
        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $baskets = Basket::query()
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->whereNull("ordered_at")
            ->get();

        foreach ($baskets as $basket){
            $basket->ordered_at = Carbon::now();
            $basket->save();
        }

        $this->shopMenu("Корзина успешно очищена!\nМеню магазина");
    }

    public function startOrder(...$config)
    {
        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $baskets = Basket::query()
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->whereNull("ordered_at")
            ->get();

        $taxSystemCode = $bot->company->vat_code ?? 0;
        $currency = "RUB";

        $prices = [];
        $receipt = [];
        $description = "";
        $summaryPrice = 0;
        $summaryCount = 0;
        foreach ($baskets as $basket) {
            $price = ($basket->product->current_price * $basket->count)*100;
            $prices[] =   [
                "label" => $basket->product->title,
                "amount" => $price
            ];
            $summaryCount +=$basket->count;
            $summaryPrice +=$price;

            $title =  $basket->product->title;
            $description .= "$title x$basket->count = ".$basket->product->current_price."руб.\n";

            $receipt[] =   (object)[
                    "description" => $basket->product->title,
                    "quantity" => " $basket->count.00",
                    "amount" => (object)[
                        "value" => $price / 100,
                        "currency" => $currency
                    ],
                    "vat_code" =>$taxSystemCode
                ];

        }

        $payload = bin2hex(Str::uuid());
        $providerToken = $bot->payment_provider_token;


        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $summaryPrice,
            'status' => 0,
            'products_info' => (object)[
                "payload" => $payloadData ?? null,
                "prices" => $prices,
            ],
        ]);

        $needs = [
            "need_name" => true,
            "need_phone_number" => true,
            "need_email" => true,
            "need_shipping_address" => false,
            "send_phone_number_to_provider" => true,
            "send_email_to_provider" => true,
            "is_flexible" => false,
            "disable_notification" => false,
            "protect_content" => false,
        ];


        $keyboard = [
            [
                ["text" => "Оплатить покупку картой", "pay" => true],
            ],

        ];



        $providerData = (object)[
            "receipt" => $receipt
        ];

        \App\Facades\BotManager::bot()
            ->replyInvoice(
                "Оформление заказа", $description, $prices, $payload, $providerToken, $currency, $needs, $keyboard,
                $providerData
            );

        $productInCart = Basket::query()
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->whereNull("ordered_at")
            ->sum("count") ?? 0;

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
                            ["text" => "🧻Очистить корзину"],
                        ],
                        [
                            ["text" => "🔥Главное меню"],
                        ],
                    ],
                ]);

        \App\Facades\BotManager::bot()
            ->replyKeyboard(
                "Оформление заказа",
                $menu->menu);

    }

    public function removeFromBasket(...$data)
    {
        $productId = $data[3] ?? null;

        if (is_null($productId)) {
            BotManager::bot()->reply("Упс... что-то пошло не так...");
            return;
        }

        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $product = Product::query()
            ->where("bot_id", $bot->id)
            ->where("id", $productId)
            ->first();

        if (is_null($product)) {
            BotManager::bot()->reply("Упс... товар не наден...");
            return;
        }

        $productInBasket = Basket::query()
            ->where("product_id", $product->id)
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->whereNull("ordered_at")
            ->first();

        if (is_null($productInBasket)) {
            $title = $product->title;
            BotManager::bot()->reply("Товар $title уже удален из корзины");
            return;
        }

        $messageId = $data[0]->message_id ?? null;

        if ($productInBasket->count - 1 > 0) {
            $productInBasket->count--;
            $productInBasket->save();

            $title = $productInBasket->product->title;
            $price = $productInBasket->count * $productInBasket->product->current_price;

            BotManager::bot()->reply("Товар $title убран из корзины. Осталось $productInBasket->count. Цена товара $price ₽");

            BotManager::bot()->editInlineKeyboard($botUser->telegram_chat_id, $messageId,[
                [
                    ["text" => "💡Информация о товаре", "callback_data" => "/detail_global_product $product->id"],
                ],
                [
                    ["text" => "🛒Добавить в корзину $product->current_price ₽ [x$productInBasket->count] ", "callback_data" => "/add_to_basket $product->id"],
                ],
                [
                    ["text" => "👎Удалить из корзины", "callback_data" => "/remove_from_basket $product->id"],
                ],
            ]);

            $this->shopMenu();
            return;
        }

        $title = $productInBasket->product->title;
        $productInBasket->delete();
        BotManager::bot()->reply("Товар $title успешно удален из корзины.");

        BotManager::bot()->editInlineKeyboard($botUser->telegram_chat_id, $messageId,[
            [
                ["text" => "💡Информация о товаре", "callback_data" => "/detail_global_product $product->id"],
            ],
            [
                ["text" => "🛒Добавить в корзину $product->current_price ₽", "callback_data" => "/add_to_basket $product->id"],
            ]
        ]);

        $this->shopMenu();
    }

    public function addToBasket(...$data)
    {
        $productId = $data[3] ?? null;

        if (is_null($productId)) {
            BotManager::bot()->reply("Упс... что-то пошло не так...");
            return;
        }

        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $product = Product::query()
            ->where("bot_id", $bot->id)
            ->where("id", $productId)
            ->first();

        if (is_null($product)) {
            BotManager::bot()->reply("Упс... товар не наден...");
            return;
        }

        $productInBasket = Basket::query()
            ->where("product_id", $product->id)
            ->where("bot_user_id", $botUser->id)
            ->where("bot_id", $bot->id)
            ->whereNull("ordered_at")
            ->first();

        if (is_null($productInBasket)) {
            $productInBasket = Basket::query()->create([
                'product_id' => $product->id,
                'count' => 1,
                'bot_user_id' => $botUser->id,
                'bot_id' => $bot->id,
                'ordered_at' => null,
            ]);
            $title = $productInBasket->product->title;
            $price = $productInBasket->count * $productInBasket->product->current_price;
            BotManager::bot()->reply("Товар $title добавлен в корзину. Цена товара $price ₽");
        } else {
            $productInBasket->count++;
            $productInBasket->save();

            $price = $productInBasket->count * $productInBasket->product->current_price;

            $title = $productInBasket->product->title;
            BotManager::bot()->reply("Товар $title добавлен в корзину в колличестве $productInBasket->count. Цена товара $price ₽");
        }

        $messageId = $data[0]->message_id ?? null;

        BotManager::bot()->editInlineKeyboard($botUser->telegram_chat_id, $messageId,[
            [
                ["text" => "💡Информация о товаре", "callback_data" => "/detail_global_product $product->id"],
            ],
            [
                ["text" => "🛒Добавить в корзину $product->current_price ₽ [x$productInBasket->count] ", "callback_data" => "/add_to_basket $product->id"],
            ],
            [
                ["text" => "👎Удалить из корзины", "callback_data" => "/remove_from_basket $product->id"],
            ],
        ]);

        $this->shopMenu();
    }

    public function productsInCategory(...$data)
    {
        $categoryId = $data[3] ?? null;
        $pageId = $data[4] ?? null;
        $this->productsPage($pageId, 5, $categoryId);
    }

    private function shopMenu($title = "Меню магазина")
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $productInCart = Basket::query()
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->whereNull("ordered_at")
            ->sum("count") ?? 0;

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
                            ["text" => "🌭Наши товары"],
                        ],
                       /* [
                            ["text" => "🕖История покупок"],
                        ],*/
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

    public function productsInBasket(...$config)
    {
        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $baskets = Basket::query()
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->whereNull("ordered_at")
            ->get();

        if (count($baskets)==0){
            BotManager::bot()->reply("Корзина пуста!");
            return;
        }

        foreach ($baskets as $basket) {

            $product = $basket->product;

            $count = $basket->count ?? 0;

            $keyboard = [
                [
                    ["text" => "🛒Добавить $product->current_price ₽ еще в корзину ", "callback_data" => "/add_to_basket $product->id"],
                ],
                [
                    ["text" => "👎Удалить из корзины", "callback_data" => "/remove_from_basket $product->id"],
                ],
            ];

            BotManager::bot()
                ->sendPhoto(
                    $botUser->telegram_chat_id,
                    $product->title . " <b>($count ед.)</b>",
                    InputFile::create($product->images[0] ?? public_path() . "/images/cashman-save-up.png"),
                    $keyboard);
        }
    }

    public function basket(...$config)
    {
        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $menu = BotMenuTemplate::query()
            ->updateOrCreate(
                [
                    'bot_id' => $bot->id,
                    'type' => 'reply',
                    'slug' => "menu_basket",

                ],
                [
                    'menu' => [

                        [
                            ["text" => "📜Товары в корзине"],
                        ],
                        [
                            ["text" => "💶Оформление заказа"],
                        ],
                        [
                            ["text" => "🌟Магазин товаров"],
                        ],
                        [
                            ["text" => "🔥Главное меню"],
                        ],
                    ],
                ]);


        $baskets = Basket::query()
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->whereNull("ordered_at")
            ->get();

        $tmpSum = 0;
        $tmpCount = 0;

        foreach ($baskets as $basket) {
            $tmpSum += $basket->product->current_price * $basket->count;
            $tmpCount += $basket->count;
        }

        \App\Facades\BotManager::bot()
            ->replyKeyboard(
                "Корзина товаров. Товаров в корзине $tmpCount ед. на сумму $tmpSum руб.",
                $menu->menu);

    }

    public function categories(...$config)
    {
        $this->categoriesPage(0, 5);
    }

    public function products(...$config)
    {
        $count = (Collection::make($config[1])
            ->where("key", "products_per_page")
            ->first())["value"] ?? 10;

        $this->productsPage(0, $count);
    }

    public function main(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $name = BotMethods::prepareUserName($botUser);

        $count = (Collection::make($config[1])
            ->where("key", "products_per_page")
            ->first())["value"] ?? 5;

        $title = (Collection::make($config[1])
            ->where("key", "shop_title")
            ->first())["value"] ?? "Меню";

        $welcome = (Collection::make($config[1])
            ->where("key", "shop_welcome_message")
            ->first())["value"] ?? "%s";

        BotManager::bot()
            ->sendPhoto(
                $botUser->telegram_chat_id,
                sprintf($welcome, $name),
                InputFile::create(public_path() . "/images/shopify.png")
            );

        $this->shopMenu($title);


    }
}
