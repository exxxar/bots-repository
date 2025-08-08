<?php

namespace App\Http\Controllers\Bots;

use App\Enums\OrderStatusEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\BusinessLogic\Methods\Classes\Banking\TinkoffBankService;
use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GoodsBasketController extends Controller
{
    public function testSbpTinkoffAutomatic(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $paymentId = $data[2] ?? null;
        $slugId = $data[3] ?? null;

        $slug = BotMenuSlug::query()
            ->find($slugId);

        if (is_null($slug))
            throw new HttpException(404, "Не найден скрипт настройки СБП!");

        $config = $slug->config ?? null;

        if (is_null($config))
            throw new HttpException(400, "Система не настроена!");

        $sbp = Collection::make($config)
            ->where("key", "sbp")
            ->first()["value"] ?? null;

        $terminalKey = $sbp["tinkoff"]["terminal_key"] ?? null;
        $terminalPassword = $sbp["tinkoff"]["terminal_password"] ?? null;
        $tax = $sbp["tinkoff"]["tax"] ?? "osn";
        $vat = $sbp["tinkoff"]["vat"] ?? "vat20";

        $tinkoff = new TinkoffBankService(config('sbp.payments.tinkoff.url'), $terminalKey, $terminalPassword);

        $state = $tinkoff->getState($paymentId);

        if ($state != "CONFIRMED") {
            BotManager::bot()
                ->reply("Оплата еще не прошла, попробуйте через некоторое время!");
            return;
        }
        $paymentData = $tinkoff->getResponse();

        $paymentId = $paymentData->PaymentId;
        $orderId = $paymentData->OrderId;
        $customerKey = $paymentData->CstomerKey;

        $order = Order::query()
            ->where("id", $orderId)
            ->whereNotNull("payed_at")
            ->first();

        $order->payed_at = Carbon::now();
        $order->status = OrderStatusEnum::Completed->value;
        $order->save();

        BotManager::bot()
            ->reply("Оплата клиента в размере $order->summary_price руб. прошла успешно!");

        $clientBotUser = BotUser::query()
            ->where("id", $order->customer_id)
            ->first();

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $clientBotUser->telegram_chat_id,
                "Ваша оплата в размере $order->summary_price руб. прошла успешно!"
            );
    }

    public function testManualPayment(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $objectId = $data[2] ?? null;

        $isSelf = ($data[3] ?? 1) == 0;

        if (!$isSelf) {
            $table = Table::query()
                ->with(["creator"])
                ->where("id", $objectId)
                ->where("bot_id", $bot->id)
                ->whereNotNull('closed_at')
                ->first();

            if (is_null($table)) {
                BotManager::bot()
                    ->reply("Данный столик уже закрыт!");
                return;
            }

            $table->closed_at = Carbon::now();
            $table->save();

            $tableBaskets = Basket::query()
                ->where("bot_id", $bot->id)
                ->where("table_id", $table->id)
                ->whereNull("ordered_at")
                ->get();

            foreach ($tableBaskets as $basket) {
                $basket->ordered_at = Carbon::now();
                $basket->save();
            }


            $orders = Order::query()
                ->where("table_id", $table->id)
                ->whereNotNull("payed_at")
                ->get();

            foreach ($orders as $order) {
                $order->payed_at = Carbon::now();
                $order->save();
            }

            BotManager::bot()
                ->reply("Столик $table->number закрыт, спасибо! Все заказы столика отмечены как оплаченные.");

            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $table->creator->telegram_chat_id,
                    "Оплата прошла успешно!"
                );
            return;
        }

        $tableBaskets = Basket::query()
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $objectId)
            ->whereNull("ordered_at")
            ->whereNotNull("table_id")
            ->get();

        $table_id = null;
        foreach ($tableBaskets as $basket) {
            $basket->ordered_at = Carbon::now();
            $basket->save();

            $table_id = $basket->table_id;
        }

        $order = Order::query()
            ->where("table_id", $table_id)
            ->whereNotNull("payed_at")
            ->first();

        $order->payed_at = Carbon::now();
        $order->save();

        $clientBotUser = BotUser::query()
            ->where("id", $objectId)
            ->first();

        BotManager::bot()
            ->reply("Оплата клиента в размере $order->summary_price руб. прошла успешно!");


        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $clientBotUser->telegram_chat_id,
                "Ваша оплата в размере $order->summary_price руб. прошла успешно!"
            );
    }
}
