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
                "key" => "payment_info",
                "value" => "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>"
            ],
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Наш магазин!",

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


        ];

        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }


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
                ["text" => "⬅ Предыдущая страница", "callback_data" => "/next_order " . ($page - 1)],
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

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Открыть магазин";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $mainImage = (Collection::make($config[1])
            ->where("key", "main_image")
            ->first())["value"] ?? null;
        //
        $keyboard = [
            [
                ["text" => "$btnText", "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/delivery-main"
                ]],
            ],

        ];

        if (is_null($mainImage))
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard("$mainText", $keyboard);
        else
            \App\Facades\BotManager::bot()
                ->replyPhoto("$mainText", $mainImage, $keyboard);

    }
}
