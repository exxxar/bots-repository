<?php

namespace App\Http\Controllers\Bots;


use App\Enums\OrderStatusEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class DeliveryServiceBotController extends Controller
{
    //


    public function orderSuccess(...$data)
    {
        $orderId = $data[2];

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        $order = Order::query()
            ->where("id", $orderId)
            ->first();

        if (is_null($order)) {
            BotManager::bot()
                ->reply("Заказ не найден!");
            return;
        }

        if ($order->deliveryman_id != $botUser->user_id) {
            BotManager::bot()
                ->reply("Заказ доставляется другим доставщиком!");
            return;
        }

        $order->status = OrderStatusEnum::Completed;
        $order->deliveryman_id = $botUser->user_id;
        $order->save();

        BotManager::bot()
            ->reply("Вы завершили заказ <b>№$order->id</b>");

    }

    public function watchOrder(...$data)
    {
        $orderId = $data[2];

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        $order = Order::query()
            ->where("id", $orderId)
            ->first();

        if (is_null($order)) {
            BotManager::bot()
                ->reply("Заказ не найден!");
            return;
        }


        $deliveryPercent = 0;

        $message = sprintf("<b>Информация о заказе:</b>
Адрес доставки: <b>%s</b>
Комментарий: <b>%s</b>
Цена заказа, руб: <b>%s</b>
Ваш процент, руб: <b>%s</b>",
            ($order->delivery_address ?? 'Не указан'),
            ($order->comment ?? 'Не указан'),
            ($order->summary_price ?? 'Не указана'),
            $deliveryPercent
        );

        $keyboard = [
            [
                ["text" => "Заказ доставлен!", "callback_data" => "/order_success $orderId"]
            ]
        ];
        BotManager::bot()
            ->replyInlineKeyboard($message, ($order->status == OrderStatusEnum::Completed->value ? [] : $keyboard));


    }

    public function takeOrder(...$data)
    {
        $orderId = $data[2];

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        $order = Order::query()
            ->where("id", $orderId)
            ->first();

        if (is_null($order)) {
            BotManager::bot()
                ->reply("Заказ не найден!");
            return;
        }

        if (!is_null($order->deliveryman_id)) {
            BotManager::bot()
                ->reply("Заказ уже взят другим доставщиком!");
            return;
        }

        $order->status = OrderStatusEnum::InDelivery;
        $order->deliveryman_id = $botUser->user_id;
        $order->save();

        BotManager::bot()
            ->reply("Вы взяли заказ <b>$order->id</b>");

    }

    public function main()
    {
        BotManager::bot()
            ->sendReplyMenu("Главное меню", "main_menu_deliveryman_1");
    }

    public function profile()
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        $moneyPerWorkDay = 0; //денег за рабочую смену
        $ordersPerWorkDay = 0; //заказов за рабочую смену

        $currentActiveOrders = 0;

        $name = BotMethods::prepareUserName($botUser);


        $message = sprintf("<b>Информация о доставщике:</b>
Ваше имя: %s
Ваш идентификатор заказчика: <code>%s</code>
Заработано денег за смену: <b>%s руб.</b>
Выполнено заказов за день: <b>%s</b>
Текущих активных заказов: <b>%s</b>",
            $name,
            $botUser->telegram_chat_id,
            $moneyPerWorkDay,
            $ordersPerWorkDay,
            $currentActiveOrders
        );


        $keyboards = [
            [
                ["text" =>
                    (!$botUser->is_work ?
                        "Начать рабочую смену" :
                        "Завершить рабочую смену"),
                    "callback_data" => "/toggle_work"]
            ]
        ];

        BotManager::bot()
            ->replyInlineKeyboard($message, $keyboards);
    }

    public function orders()
    {

        BotManager::bot()
            ->sendReplyMenu("Заказы", "main_menu_deliveryman_3");
    }

    public function statistic()
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        BotManager::bot()
            ->sendReplyMenu("Персональная статистика курьера", "main_menu_deliveryman_2");
    }

    public function statisticDelivery()
    {

        $summaryDeliveryCount = 0;
        $lastYearDeliveryCount = 0;
        $lastMonthDeliveryCount = 0;
        $lastWeekDeliveryCount = 0;
        $lastDecadeDeliveryCount = 0;
        $currentDayDeliveryCount = 0;
        $failedDeliveryCount = 0;
        $positiveReviewDeliveryCount = 0;
        $negativeReviewDeliveryCount = 0;
        $message = sprintf("<b>Статистика доставок:</b>
❗ Всего выполнено заказов: <b>%s</b>
❗ Заказов за последний год: <b>%s</b>
❗ Заказов за последний месяц: <b>%s</b>
❗ Заказов за последнюю неделю: <b>%s</b>
❗ Заказов за последние 10 дней: <b>%s</b>
❗ Заказов за текущий день: <b>%s</b>
❗ Неудачных заказов: <b>%s</b>
❗ Число заказов с положительным отзывом: <b>%s</b>
❗ Число заказов с отрицательным отзывом: <b>%s</b>",
            $summaryDeliveryCount,
            $lastYearDeliveryCount,
            $lastMonthDeliveryCount,
            $lastWeekDeliveryCount,
            $lastDecadeDeliveryCount,
            $currentDayDeliveryCount,
            $failedDeliveryCount,
            $positiveReviewDeliveryCount,
            $negativeReviewDeliveryCount

        );
        BotManager::bot()
            ->reply("$message");
    }

    public function statisticMoney()
    {
        $summaryOrdersMoneyCount = 0;
        $summaryDeliveryMoneyCount = 0;
        $lastYearDeliveryCount = 0;
        $lastMonthDeliveryCount = 0;
        $lastWeekDeliveryCount = 0;
        $lastDecadeDeliveryCount = 0;
        $currentDayDeliveryCount = 0;

        $message = sprintf("<b>Статистика дохода доставщика:</b>
❗ Всего сумма заказанных товаров: <b>%s</b>
❗ Всего сумма оплаченной доставки: <b>%s</b>
❗ Сумма за последний год: <b>%s</b>
❗ Сумма за последний месяц: <b>%s</b>
❗ Сумма за последнюю неделю: <b>%s</b>
❗ Сумма за последние 10 дней: <b>%s</b>
❗ Сумма за текущий день: <b>%s</b>",
            $summaryOrdersMoneyCount,
            $summaryDeliveryMoneyCount,
            $lastYearDeliveryCount,
            $lastMonthDeliveryCount,
            $lastWeekDeliveryCount,
            $lastDecadeDeliveryCount,
            $currentDayDeliveryCount
        );

        BotManager::bot()
            ->reply("$message");
    }

    public function currentOrders()
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }


        $orders = Order::query()
            ->where("deliveryman_id", $botUser->user_id)
            ->where("status", OrderStatusEnum::InDelivery)
            ->get();


        $ordersCount = $orders->count();


        if ($ordersCount == 0) {
            BotManager::bot()
                ->reply("Нет взятых в работу заказов!");
            return;
        }

        $tmp = "<b>Заказы в работе ($ordersCount):</b>\n";

        $keyboards = [];

        $tmpKeyboards = [];
        $index = 0;
        foreach ($orders as $item) {
            $tmp .= "<b>#" . $item->id . "</b> <em>" .
                ($item->summary_price ?? 0) . "руб </em> " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";

            if (count($tmpKeyboards) < 2)
                $tmpKeyboards[] = ["text" => "Детали к №" . ($index + 1), "callback_data" => "/watch_order " . $item->id];
            else {
                $keyboards[] = $tmpKeyboards;
                $tmpKeyboards = [["text" => "Детали к №" . ($index + 1), "callback_data" => "/watch_order " . $item->id]];
            }


            $index++;

        }
        if (count($tmpKeyboards) > 0)
            $keyboards[] = $tmpKeyboards;

        if ($ordersCount > 10)

            //todo: сделать /more_orders метод
            $keyboards[] = [
                ["text" => "Загрузить еще", "callback_data" => "/more_orders $botUser->bot_id $botUser->user_id 1"]
            ];


        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard($tmp, $keyboards);
    }

    public function finishedOrders()
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        $orders = Order::query()
            ->where("deliveryman_id", $botUser->user_id)
            ->where("status", OrderStatusEnum::Completed)
            ->get();

        $ordersCount = $orders->count();


        if ($ordersCount == 0) {
            BotManager::bot()
                ->reply("Нет взятых в работу заказов!");
            return;
        }

        $tmp = "<b>Завершенных заказов ($ordersCount):</b>\n";

        $keyboards = [];

        $tmpKeyboards = [];
        $index = 0;
        foreach ($orders as $item) {
            $tmp .= "<b>#" . $item->id . "</b> <em>" .
                ($item->summary_price ?? 0) . "руб </em> " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";

            if (count($tmpKeyboards) < 2)
                $tmpKeyboards[] = ["text" => "Детали к №" . ($index + 1), "callback_data" => "/watch_order " . $item->id];
            else {
                $keyboards[] = $tmpKeyboards;
                $tmpKeyboards = [["text" => "Детали к №" . ($index + 1), "callback_data" => "/watch_order " . $item->id]];
            }


            $index++;

        }
        if (count($tmpKeyboards) > 0)
            $keyboards[] = $tmpKeyboards;

        if ($ordersCount > 10)

            //todo: сделать /more_orders метод
            $keyboards[] = [
                ["text" => "Загрузить еще", "callback_data" => "/more_completed_orders $botUser->bot_id $botUser->user_id 1"]
            ];


        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard($tmp, $keyboards);
    }

    public function support()
    {
        BotManager::bot()
            ->reply("support");
    }

    public function rules()
    {
        BotManager::bot()
            ->reply("https://telegra.ph/Pravila-raboty-v-servise-DeliveryRocket-04-26");
    }

    public function searchOrders()
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        if (!$botUser->is_work) {
            BotManager::bot()
                ->reply("Вы не выставили статус 'Работаю' в своём профиле!");
            return;
        }

        $orders = Order::query()
            ->where("status", OrderStatusEnum::NewOrder)
            ->where("need_delivery", true)
            ->whereNull("deliveryman_id")
            ->get();

        if (count($orders) == 0) {
            BotManager::bot()
                ->reply("Доступных заказов не обноружено:(");
            return;
        }

        $message = "";
        $keyboards = [];

        $tmpKeyboards = [];
        $index = 0;
        foreach ($orders as $item) {
            $message .= "<b>#" . $item->id . "</b> <em>" .
                ($item->summary_price ?? 0) . "руб </em> " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";

            if (count($tmpKeyboards) < 4)
                $tmpKeyboards[] = ["text" => "Заказ №" . ($index + 1), "callback_data" => "/take_order " . $item->id];
            else {
                $keyboards[] = $tmpKeyboards;
                $tmpKeyboards = [["text" => "Заказ №" . ($index + 1), "callback_data" => "/take_order " . $item->id]];
            }

            $index++;

        }

        if (count($tmpKeyboards) > 0)
            $keyboards[] = $tmpKeyboards;

        BotManager::bot()
            ->replyInlineKeyboard("$message", $keyboards);
    }

    public function toggleWork()
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_deliveryman) {
            BotManager::bot()
                ->sendInlineMenu("Вы еще не стали доставщиком! Заполните анкету!",
                    "deliveryman_form_1");
            return;
        }

        $botUser->is_work = !$botUser->is_work;
        $botUser->save();

        $status = $botUser->is_work ? "Работаю" : "Не работаю";
        BotManager::bot()
            ->reply("Вы изменили свой рабочий статус на <b>$status</b>");

    }

}
