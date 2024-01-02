<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Order;
use App\Models\ReferralHistory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StartCodesHandlerController extends Controller
{
    public function paymentAction(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();

        $code = $data[1] ?? null;

        $bot_user_id = $data[2] ?? null;
        $bot_id = $data[3] ?? null;
        $value = intval($data[4] ?? 0);

        if ($code != "005") {
            BotManager::bot()
                ->reply("Ошибка типа данных!");
            return;
        }

        $botUserPayment = BotUser::query()
            ->where("id", $bot_user_id)
            ->first();

        $botPayment = Bot::query()
            ->where("id", $bot_id)
            ->first();

        if (is_null($botPayment) || is_null($botUserPayment)) {
            BotManager::bot()
                ->reply("Ошибка передачи платежных данных!");
            return;
        }

        if (!$botUserPayment->is_admin) {
            BotManager::bot()
                ->reply("У вас нет прав доступа!");
            return;
        }

        $prices = [
            [
                "label" => "Оплата услуг сервиса CashMan",
                "amount" => $value * 100
            ]
        ];
        $payload = bin2hex(Str::uuid());

        $providerToken = $bot->payment_provider_token;
        $currency = "RUB";

        Transaction::query()->create([
            'user_id' => $botUserPayment->user_id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $value,
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
                ["text" => "Оплатить $value ₽", "pay" => true],
            ],

        ];

        $providerData = (object)[
            "receipt" => [
                (object)[
                    "description" => "Оплата услуг сервиса CashMan",
                    "quantity" => "1.00",
                    "amount" => (object)[
                        "value" => $value,
                        "currency" => $currency
                    ],
                    "vat_code" => 0
                ]
            ]
        ];

        \App\Facades\BotManager::bot()
            ->replyInvoice(
                "CashMan:Оплата", "Оплата услуг сервиса CashMan", $prices, $payload, $providerToken, $currency, $needs, $keyboard,
                $providerData
            );;
    }

    public function slugAction(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();

        $code = $data[1] ?? null;
        $request_id = $data[2] ?? null;
        $slug_id = $data[3] ?? 'route';

        if ($code != "002")
            return;

        $text = "Административное меню системы бонусных накоплений";
        $path = env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slug_id&user=$request_id#/admin-bonus-product";

        BotManager::bot()->replyInlineKeyboard(
            $text,
            [
                [
                    ["text" => "\xF0\x9F\x8E\xB0Перейти в административное меню",
                        "web_app" => [
                            "url" => $path
                        ]
                    ],
                ]
            ]
        );

    }

    public function orderAction(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();

        $code = $data[1] ?? null;
        $request_id = $data[2] ?? null;
        $order_id = $data[3] ?? null;

        if ($code != "001")
            return;


        $this->userOrder($request_id, $order_id);


    }

    public function referralAction(...$data)
    {

        $bot = BotManager::bot()
            ->getSelf();

        $botUser = BotManager::bot()
            ->currentBotUser();

        $code = $data[1] ?? null;
        $request_id = $data[2] ?? null;

        $message = $bot->welcome_message ?? null;

        if ($botUser->is_admin) {
            switch ($code) {
                default:
                case "001":
                    $text = "Основная административная панель";
                    $path = env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route&user=$request_id#/admin-main";
                    break;

                case "003":
                    $text = "Обратная связь с пользователем";
                    $path = env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route&user=$request_id#/admin-main";

                    break;


            }


            BotManager::bot()->replyInlineKeyboard(
                $text,
                [
                    [
                        ["text" => "\xF0\x9F\x8E\xB0Перейти в административное меню",
                            "web_app" => [
                                "url" => $path
                            ]
                        ],
                    ]
                ]
            );


        }

        if ($code == "004") {
            BotManager::bot()->runPage($request_id);
            return;
        }


        if (BotManager::bot()->currentBotUser()->telegram_chat_id == $request_id) {
            BotManager::bot()
                ->reply(
                    "Вы перешли по своей собственной ссылке... вы, конечно, себе друг, но CashBack достанется кому-то одному..."
                );

            return;

        }

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $request_id)
            ->where("bot_id", BotManager::bot()->getSelf()->id)
            ->first();


        $ref = ReferralHistory::query()
            ->where("user_sender_id", $userBotUser->user_id ?? null)
            ->where("user_recipient_id", $botUser->user_id ?? null)
            ->where("bot_id", $botUser->bot_id)
            ->first();

        if (is_null($ref)) {
            ReferralHistory::query()->create([
                'user_sender_id' => $userBotUser->user_id,
                'user_recipient_id' => $botUser->user_id,
                'bot_id' => $botUser->bot_id,
                'activated' => true,
            ]);

            $userName1 = BotMethods::prepareUserName($botUser);
            $userName2 = BotMethods::prepareUserName($userBotUser);

            $botUser->parent_id = $userBotUser->id;
            $botUser->save();

            BotMethods::bot()
                ->whereId($botUser->bot_id)
                ->sendMessage(
                    $userBotUser->telegram_chat_id,
                    "По вашей ссылке перешел пользователь $userName1"
                )
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Вас и вашего друга $userName2 теперь объеденяет еще и CashBack;)"
                );
        }


        if (is_null($userBotUser)) {
            BotManager::bot()->reply("Данный код не корректный!");
            return;
        }

        $userBotUser->user_in_location = true;
        $userBotUser->save();

        BotManager::bot()->reply($message);


    }

    private function userOrder($telegramChatId, $orderId)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotUser::query()
            ->where("telegram_chat_id", $telegramChatId)
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($botUser)) {
            BotManager::bot()
                ->reply("Упс... Клиент не найден");
            return;
        }

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->where("id", $orderId)
            ->orderBy("updated_at", "DESC")
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


        $statuses = ["Новый заказ", "В процессе доставки", "Завершен", "Отменен"];

        $address = $order->address ?? 'не указан';
        $name = $order->receiver_name ?? 'не указан';
        $phone = $order->receiver_phone ?? 'не указан';
        $status = $statuses[$order->status ?? 0];

        $note = sprintf("Имя заказчика: %s\nАдрес доставки: %s\nТелефон: %s\nСтатус: %s\nЗаметки к заказу: %s\n",
            $name,
            $address,
            $phone,
            $status,
            $order->delivery_note ?? 'не указана'
        );

        $text = "Заказ #$order->id\nПрислан из $from:\n<em>$products</em>Дата заказа: " . Carbon::parse($order->created_at)
                ->format("Y-m-d H:i:s") . "\n\n<b>Заметка для доставщика:</b>\n$note";


        BotManager::bot()
            ->sendMessage(
                $botUser->telegram_chat_id,
                $text);

    }
}
