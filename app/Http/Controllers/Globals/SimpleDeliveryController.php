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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    public function loadData(Request $request): mixed
    {
        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;
        $slug = $request->slug ?? null;

        if (is_null($bot) || is_null($botUser) || is_null($slug))
            throw new HttpException("Не заданы необходимые параметры функции", 400);


        $dictionary = [
            "delivery_price_text" => "Цена доставки рассчитывается курьером",
            "disabled_text" => "Временно недоступно!",
            "min_price" => 100,
            "price_per_km" => 100,
            "min_price_for_cashback" => 2000,
            "is_disabled" => false,
            "can_use_card" => false,
            "can_use_cash" => true,
            "can_buy_after_closing" => true,
            "min_base_delivery_price" => 0,
            "menu_list_type" => 0,
            "max_tables" => 0,
            "shop_coords" => "0,0",
            "need_table_list" => false,
            "need_category_by_page" => true,
            "need_pay_after_call" => true,
            "is_product_list" => false,
            "need_promo_code" => true,
            "need_automatic_delivery_request" => true,
            "need_person_counter" => true,
            "need_bonuses_section" => true,
            "need_health_restrictions" => true,
            "need_prizes_from_wheel_of_fortune" => true,
            "selected_script_id" => null,
            "payment_token" => null,

            "can_use_sbp" => false,
            "sbp" => (object)[
                "selected_sbp_bank" => "tinkoff",
                "tinkoff" => (object)[
                    "terminal_key" => null,
                    "terminal_password" => null,
                    "tax" => null,
                    "vat" => null,
                ],
                "sber" => null
            ],
            "free_shipping_starts_from" => 0,
            "shop_display_type" => 0,
            "payment_info" => "Текст не найден",
            "wheel_of_fortune" => (object)[
                "rules" => "Правила колеса фортуны",
                "can_play" => false,
                "items" => []
            ],
            "win_message" => "{{name}}, вы приняли участие в розыгрыше и выиграли приз {{prize}}. Наш менеджер свяжется с вами в ближайшее время!",
        ];


        if (!is_null($slug->config ?? null)) {
            $tmp = [];

            foreach ($slug->config ?? [] as $item) {
                $item = (object)$item;
                $tmp[$item->key] = is_null($item->value ?? null) ? ($dictionary[$item->key] ?? null) : $item->value;
            }

            foreach ($dictionary as $key => $item) {
                if (!isset($tmp[$key]))
                    $tmp[$key] = $item;
            }


            $tmp["is_admin"] = $botUser->is_admin || $botUser->is_manager;

            return new ShopConfigPublicResource((object)$tmp);
        }
        return response()->json($dictionary);
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

    public function formWheelOfFortuneV3Callback(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "id" => "required",
            "description" => "required"
        ]);

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;
        $slug = $request->slug ?? null;

        if (is_null($bot) || is_null($botUser) || is_null($slug))
            throw new HttpException(400, "Не заданы необходимые параметры функции");

        $maxAttempts = 1;

        $callbackChannel = $bot->order_channel ?? null;

        $winMessage = (Collection::make($slug->config)
            ->where("key", "win_message")
            ->first())["value"] ?? "%s, вы приняли участие в розыгрыше и выиграли приз под номером %s (%s). Наш менеджер свяжется с вами в ближайшее время!";

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            throw new HttpException(404, "Не найдено игровое состояние");

        if (!is_null($action->completed_at ?? null))
            throw new HttpException(403, "Получение призов для игрока недоступно");

        $action->current_attempts++;

        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();


        $winNumber = $request->id ?? 0;
        $winnerName = $botUser->name ?? 'Имя не указано';
        $winnerPhone = $botUser->phone ?? 'Телефон не указан';
        $mark = $request->mark ?? 'Без указания способа получения';
        $winnerDescription = ($request->description ?? 'Без описания') . ", <b>способ получения приза:" . $mark . "</b>";

        $username = $botUser->username ?? null;

        /* $tmp = $action->data ?? [];

         $tmp[] = (object)[
             "bgColor" => $request->bgColor ?? null,
             "color" => $request->color ?? null,
             "description" => $request->description ?? null,
             "id" => $request->id ?? null,
             "mark" => $request->mark ?? null,
             "value" => $request->value ?? null,

         ];*/

        $action->data = [
            (object)[
                "bgColor" => $request->bgColor ?? null,
                "color" => $request->color ?? null,
                "description" => $request->description ?? null,
                "id" => $request->id ?? null,
                "mark" => $request->mark ?? null,
                "value" => $request->value ?? null,

            ]
        ];

        $link = "https://t.me/$bot->bot_domain?start=" . base64_encode("003$botUser->telegram_chat_id");

        $action->max_attempts = $maxAttempts;
        $action->save();

        $thread = $bot->topics["actions"] ?? null;

        $vowels = ["(", ")", "-"];
        $filteredPhone = str_replace($vowels, "", $winnerPhone);

        $winMessage = str_replace(["{{name}}"], $winnerName ?? 'имя не указано', $winMessage);
        $winMessage = str_replace(["{{phone}}"], $winnerPhone ?? 'телефон не указан', $winMessage);
        $winMessage = str_replace(["{{prize}}"], $winnerDescription ?? 'описание приза не указано', $winMessage);
        $winMessage = str_replace(["{{username}}"], "@" . ($username ?? 'имя не указано'), $winMessage);

        \App\Facades\BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage($botUser
                ->telegram_chat_id,
                $winMessage)
            ->sendInlineKeyboard($callbackChannel,
                "Участник $filteredPhone ($winnerName " . ($username ? "@$username" : 'Домен не указан') . ") принял участие в розыгрыше и выиграл приз №$winNumber ( $winnerDescription ) - свяжитесь с ним для дальнейших указаний", [
                    [
                        ["text" => "Написать пользователю ответ", "url" => $link]
                    ]
                ], $thread);


        $nextWinPageId = (Collection::make($slug->config)
            ->where("key", "next_win_page_id")
            ->first())["value"] ?? null;

        if (!is_null($nextWinPageId)) {
            $isRun = BotManager::bot()
                ->runPage($nextWinPageId, $bot, $botUser);

            if (!$isRun)
                BotManager::bot()
                    ->runSlug($nextWinPageId, $bot, $botUser);
        }


        return response()->noContent();
    }

    public function formWheelOfFortuneV3Prepare(Request $request): \Illuminate\Http\JsonResponse
    {

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;
        $slug = $request->slug ?? null;


        if (is_null($bot) || is_null($botUser) || is_null($slug))
            throw new HttpException(400, "Не заданы необходимые параметры функции");

        $maxAttempts = 1;

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();


        if (is_null($action))

            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0,
                    'bot_user_id' => $botUser->id
                ]);


        $action->max_attempts = $maxAttempts;

        if (!is_null($action->completed_at ?? null)) {
            $interval = (Collection::make($slug->config)
                ->where("key", "interval")
                ->first())["value"] ?? 1;

            if (Carbon::now()->timestamp - Carbon::parse($action->completed_at)->timestamp >= 86400 * $interval) {
                $action->current_attempts = 0;
                $action->completed_at = null;
            }
        }


        if (is_null($action->completed_at ?? null)) {
            $action->current_attempts = 0;
            $action->completed_at = null;
        }


        if (is_null($action->data ?? null))
            $action->current_attempts = 0;

        $action->save();

        return response()->json([
            "action" => new ActionStatusResource($action),
        ]);
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


        $keyboard = [
            [
                ["text" => "💎Меню магазина", "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId#/s/menu"]
                ],
            ],

            /*  [
                  ["text" => "😎Контакты", "web_app" => [
                      "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId#/s/contacts"]
                  ],
              ],*/
        ];


        if (is_null($mainImage))
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($isDisabled ? "$disabledText" : "$mainText", $isDisabled ? [] : $keyboard);
        else
            \App\Facades\BotManager::bot()
                ->replyPhoto($isDisabled ? "$disabledText" : "$mainText", $mainImage, $isDisabled ? [] : $keyboard);

    }
}
