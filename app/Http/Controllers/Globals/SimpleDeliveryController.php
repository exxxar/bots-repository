<?php

namespace App\Http\Controllers\Globals;

use App\Classes\BotMethods;
use App\Classes\SlugController;
use App\Enums\OrderStatusEnum;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class SimpleDeliveryController extends SlugController
{
    public function config(Bot $bot)
    {


        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_simple_delivery_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],

            [
                'command' => ".*Мини-доставка",
                'comment' => "Скрипт добавляет возможность заказа товара на доставку",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "yandex_geocoder",
                "description" => "ключ от АПИ яндекс ГЕО",
                "value" => null,

            ],

            [
                "type" => "text",
                "key" => "yandex_map_link",
                "description" => "Ссылка на ваше расположение на карте",
                "value" => null,

            ],
            [
                "type" => "text",
                "key" => "free_shipping_starts_from",
                "description" => "Бесплатная доставка от",
                "value" => 0,

            ],
            [
                "type" => "text",
                "key" => "min_base_delivery_price",
                "description" => "Минимальная цена доставки для расчёта",
                "value" => 0,

            ],
            [
                "type" => "text",
                "key" => "price_per_km",
                "description" => "Цена доставки за КМ",
                "value" => 80,

            ],
            [
                "type" => "text",
                "key" => "payment_info",
                "value" => "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>"
            ],
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Наш магазин!",

            ],

            [
                "type" => "text",
                "key" => "shop_theme_id",
                "description" => "Идентификатор темы магазина",
                "value" => 0,

            ],

            [
                "type" => "text",
                "key" => "delivery_price_text",
                "description" => "Описание ценника на доставку",
                "value" => "1000 руб.",
            ],
            [
                "type" => "text",
                "key" => "menu_list_type",
                "description" => "Тип отображения меню в магазине: 0 - списком, 1 - карточками",
                "value" => 0,
            ],
            [
                "type" => "boolean",
                "key" => "need_category_by_page",
                "description" => "Каждая категория - отдельная страница",
                "value" => true,
            ],
            [
                "type" => "text",
                "key" => "min_price",
                "description" => "Минимальный порог заказа",
                "value" => 100,
            ],
            [
                "type" => "boolean",
                "key" => "is_disabled",
                "value" => false,

            ],
            [
                "type" => "boolean",
                "key" => "use_payment_system",
                "value" => false,

            ],

            [
                "type" => "boolean",
                "key" => "can_use_cash",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "can_use_card",
                "value" => true,

            ],

            [
                "type" => "boolean",
                "key" => "need_pay_after_call",
                "value" => false,

            ],

            [
                "type" => "text",
                "key" => "disabled_text",
                "value" => "Магазин временно не доступен",

            ],
            [
                "type" => "image",
                "key" => "main_image",
                "value" => null,

            ],

            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Перейти",

            ],

            [
                "type" => "boolean",
                "key" => "need_name",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "need_phone_number",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "need_email",
                "value" => false,

            ],
            [
                "type" => "boolean",
                "key" => "need_shipping_address",
                "value" => false,

            ],
            [
                "type" => "boolean",
                "key" => "need_send_email_to_provider",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "need_send_phone_number_to_provider",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "is_flexible",
                "value" => false,

            ],
            [
                "type" => "text",
                "key" => "tax_system_code",
                "value" => 1,

            ],
            [
                "type" => "boolean",
                "key" => "need_disable_notification",
                "value" => false,

            ],
            [
                "type" => "boolean",
                "key" => "need_protect_content",
                "value" => false,

            ],
            [
                "type" => "text",
                "key" => "btn_payment_text",
                "value" => "Оплатить заказ",

            ],
            [
                "type" => "text",
                "key" => "checkout_description",
                "value" => "Описание товара",

            ],
            [
                "type" => "text",
                "key" => "checkout_title",
                "value" => "Товар",

            ],
            [
                "type" => "geo",
                "key" => "shop_coords",
                "value" => null,

            ],


        ];


        $mainScript->config = $params;
        $mainScript->save();


        BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_simple_delivery_my_orders",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],

            [
                'command' => ".*Мои заказы из мини-доставки",
                'comment' => "Скрипт добавляет возможность просмотра истории своих заказов из мини-доставки",
            ]);

    }

    public function loadClientProfile(Request $request)
    {

    }

    public function loadData(Request $request)
    {
        $slug = $request->slug;

        return response()->json(
            [
                'delivery_price_text' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "delivery_price_text")
                    ->first())["value"] ?? "Цена доставки рассчитывается курьером" : "Цена доставки рассчитывается курьером",
                'min_price' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "min_price")
                    ->first())["value"] ?? 100 : 100,
                'min_price_for_cashback' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "min_price_for_cashback")
                    ->first())["value"] ?? 2000 : 2000,
                'can_use_card' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "can_use_card")
                    ->first())["value"] ?? false : false,
                'can_use_cash' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "can_use_cash")
                    ->first())["value"] ?? true : true,

                'menu_list_type' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "menu_list_type")
                    ->first())["value"] ?? 0 : 0,
                'need_category_by_page' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "need_category_by_page")
                    ->first())["value"] ?? true : true,
                'need_pay_after_call' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "need_pay_after_call")
                    ->first())["value"] ?? false : false,
                'free_shipping_starts_from' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "free_shipping_starts_from")
                    ->first())["value"] ?? 0 : 0,
                'payment_info' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "payment_info")
                    ->first())["value"] ?? "Текст не найден" : "Текст не найден",
                'yandex_map_link' => !is_null($slug->config ?? null) ? (Collection::make($slug->config)
                    ->where("key", "yandex_map_link")
                    ->first())["value"] ?? null : null,


            ]
        );
    }

    private function orderPage($page = 0, $messageId = null)
    {
        $count = 1;
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->orderBy("updated_at", "DESC");

        $allOrdersCount = $order->count();

        $order = $order
            ->skip($page * $count)
            ->take($count)
            ->first();


        if (is_null($order)) {
            BotManager::bot()
                ->reply("Упс... Заказов нет:(");
            return;
        }


        $from = "не указан источник";
        $products = "нет продуктов";
        if (!empty($order->product_details)) {

            $products = "";

            foreach ($order->product_details as $detail) {
                $detail = (object)$detail;
                $from = $detail->from ?? 'Не указано';
                if (is_array($detail->products)) {
                    foreach ($detail->products as $product) {
                        $product = (object)$product;
                        $products .= "$product->title x$product->count = $product->price ₽\n";
                    }

                } else
                    $products .= "Текст заказа: $detail->products\n";

            }
        }


        $text = "Заказ #$order->id\nПрислан из $from:\n<em>$products</em>Дата заказа: " . Carbon::parse($order->created_at)
                ->format("Y-m-d H:i:s");

        $keyboard = [];

        if ($page == 0)
            $keyboard[] = [
                ["text" => "Следующая страница ➡", "callback_data" => "/next_order " . ($page + 1)],
            ];

        if ($page >= 1 && $page + 1 < $allOrdersCount)
            $keyboard[] = [
                ["text" => "⬅ " . ($page) . "/$allOrdersCount", "callback_data" => "/next_order " . ($page - 1)],
                ["text" => ($page + 2) . "/$allOrdersCount ➡", "callback_data" => "/next_order " . ($page + 1)],
            ];

        if ($page + 1 == $allOrdersCount)
            $keyboard[] = [
                ["text" => "⬅ Предыдущая страница", "callback_data" => "/next_order " . ($page > 0 ? $page - 1 : 0)],
            ];

        if ($order->status == OrderStatusEnum::InDelivery->value)
            $keyboard[] = [
                ["text" => "🔎Где сейчас доставщик?", "callback_data" => "/watch_for_deliveryman " . ($order->id)],
            ];

        if (!is_null($messageId)) {

            BotManager::bot()
                ->editMessageText(
                    $botUser->telegram_chat_id,
                    $messageId,
                    $text,
                    $keyboard
                );

            return;
        }

        BotManager::bot()
            ->sendInlineKeyboard(
                $botUser->telegram_chat_id,
                $text,
                $keyboard);

    }


    public function watchForDeliveryman(...$data)
    {
        $orderId = $data[3] ?? null;

        if (is_null($orderId)) {
            BotManager::bot()
                ->reply("Упс... Заказ не найден!");
            return;
        }

        $order = Order::query()->find($orderId);

        if (is_null($order)) {
            BotManager::bot()
                ->reply("Упс... Заказ не найден!");
            return;
        }

        if (($order->status ?? OrderStatusEnum::Completed->value) == OrderStatusEnum::Completed->value) {
            BotManager::bot()
                ->reply("Заказ уже доставлен, позиция доставщика не отслеживается");
            return;
        }

        if (($order->deliveryman_latitude ?? 0) == 0 || ($order->deliveryman_longitude ?? 0) == 0) {
            BotManager::bot()
                ->reply("Заказ не отслеживается в данный момент");
            return;
        }

        BotManager::bot()
            ->replyLocation($order->deliveryman_latitude, $order->deliveryman_longitude);

    }

    public function nextOrders(...$data)
    {
        $pageId = $data[3] ?? null;
        $messageId = $data[0]->message_id ?? null;
        $this->orderPage($pageId, $messageId);
    }

    public function myOrders(...$config)
    {
        $this->orderPage();
    }

    public function simpleDeliveryScript(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Сервис доставки";

        $disabledText = (Collection::make($config[1])
            ->where("key", "disabled_text")
            ->first())["value"] ?? "Сервис доставки недоступен";

        $isDisabled = (Collection::make($config[1])
            ->where("key", "is_disabled")
            ->first())["value"] ?? false;

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Открыть магазин";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $mainImage = (Collection::make($config[1])
            ->where("key", "main_image")
            ->first())["value"] ?? null;

        $shopThemeId = (Collection::make($config[1])
            ->where("key", "shop_theme_id")
            ->first())["value"] ?? 0;


        switch ($shopThemeId) {
            default:
            case 0:
                $keyboard = [
                    [
                        ["text" => "$btnText", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/delivery-main"
                        ]],
                    ],
                ];


                break;
            case 1:

                $keyboard = [
                    [
                        ["text" => "$btnText", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId#/s/catalog"]
                        ],
                    ],
                    [
                        ["text" => "🛒Корзина", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId#/s/cart"]
                        ],
                    ],
                    [
                        ["text" => "😎Мой профиль", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId#/s/profile"]
                        ],
                    ],
                    /*  [
                          ["text" => "😎Контакты", "web_app" => [
                              "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId#/s/contacts"]
                          ],
                      ],*/
                ];


                break;
        }


        if (is_null($mainImage))
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($isDisabled ? "$disabledText" : "$mainText", $isDisabled ? [] : $keyboard);
        else
            \App\Facades\BotManager::bot()
                ->replyPhoto($isDisabled ? "$disabledText" : "$mainText", $mainImage, $isDisabled ? [] : $keyboard);

    }
}
