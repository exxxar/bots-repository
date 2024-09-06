<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\InlineBotController;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\FileUpload\InputFile;

include_once "bots/cashback.php";
include_once "bots/shop.php";
include_once "bots/delivery.php";
include_once "bots/funnel.php";
include_once "bots/manages.php";
include_once "bots/admin.php";
include_once "bots/global.php";

BotManager::bot()
    ->controller(\App\Http\Controllers\Bots\SystemDiagnosticController::class)
    ->route("/.*Мой id|.*мой id", "getMyId")
    ->route("/about", "aboutBot")
    ->route("/help", "helpBot")
    ->route("/democircle", "democircle")
    ->route("/testdiсe", "demodice")
    ->route("/testconfig", "testConfig")
    ->route("/diagnostic", "getDiagnosticTable")
    ->route("/media", "getMedia")
    ->route("/clear_all_notes", "clearAllNotes")
    ->route("/notes", "getNotes")
    ->route("/botpay", "payForBot")
    ->route("/pay_tax_fee ([0-9]+)", "payTaxFee")
    ->route("/send_review ([0-9]+)", "sendReview")
    ->route("/send_tips ([0-9]+)", "sendTips")
    ->route("/auto_send_cashback ([0-9]+)", "autoSendCashBack")
    ->route("/send_to_delivery ([0-9]+)", "sendToDelivery")
    ->route("/success_complete_order ([0-9]+)", "successCompleteOrder")
    ->route("/remove_media_file ([0-9]+)", "removeMediaFile")
    ->route("/remove_all_media_file ([a-zA-Z0-9]+)", "removeAllMediaFileByType")
    ->route("/remove_notes ([0-9]+)", "removeNotes")
    ->route("/show_media_file ([0-9]+)", "showMediaFile")
    ->route("/show_document ([0-9]+)", "showDocument")
    ->route("/accept_verified_document ([0-9]+)", "acceptVerifiedDocument")
    ->route("/decline_verified_document ([0-9]+)", "declineVerifiedDocument")
    ->route("/cashman", "cashmanPayment")
    ->route("/reset_all_bot_users (yes|[0-9a-zA-Z]+)", "resetAllBotUsers")
    ->route("/start ([0-9a-zA-Z=]+)", "startWithParam")
    ->route("/diagnostic ([0-9]+)", "getDiagnosticTable")
   // ->fallbackDocument("uploadAnyKindOfMedia")
    ->fallbackAudio("uploadAnyKindOfMedia")
    ->fallbackSticker("uploadAnyKindOfMedia")
    ->fallbackVideo("uploadAnyKindOfMedia");


BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

BotManager::bot()
    ->fallbackDocument(function (...$data) {
        Log::info(print_r($data, true));

        $caption = $data[2] ?? null;
        $files = $data[3] ?? null;

        Log::info(print_r($files, true));
        $botUser = BotManager::bot()->currentBotUser();
        $bot = BotManager::bot()->getSelf();
        $fileToSend = $files[count($files) - 1]->file_id ?? null;

        $count = 0;

        if ($botUser->is_admin || $botUser->is_manager) {
            $media = \App\Models\BotMedia::query()->updateOrCreate([
                'bot_id' => $bot->id,
                'bot_user_id' => $botUser->id,
                'file_id' => $fileToSend,
            ], [
                'caption' => $caption,
                'type' => "photo"
            ]);

            $tmp = "<b>#$media->id</b> (<code>$fileToSend</code>),";
            $count++;

            BotManager::bot()
                ->reply("Документы ($count шт.) добавлены в медиа пространство бота с идентификаторами: $tmp - для просмотра доступных медиа используйте /media");

        }


        $caption = !is_null($caption) ? $caption : 'Без подписи';

        /*if (!str_contains($caption, "оплата")) {
            BotManager::bot()->reply("Фотография в описании должна содержать ключевое слово, например: оплата");
            return;
        }*/

        $channel = $bot->order_channel ?? $bot->main_channel ?? null;

        if (is_null($fileToSend) || is_null($channel)) {
            BotManager::bot()->reply("Ошибка отправки документа!");
            return;
        }


        $name = \App\Facades\BotMethods::prepareUserName($botUser);

        $id = $botUser->telegram_chat_id;

        $phone = $botUser->phone ?? 'Не указан';

        $link = "https://t.me/$bot->bot_domain?start=" .
            base64_encode("001" . $botUser->telegram_chat_id);

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->orderBy("updated_at", "DESC")
            ->first();

        $historyLink = "https://t.me/$bot->bot_domain?start=" . (
            !is_null($order) ?
                base64_encode("001" . $botUser->telegram_chat_id . "O" . $order->id) :
                base64_encode("001" . $botUser->telegram_chat_id)
            );

        $thread = $bot->topics["orders"] ?? null;


        if (is_null($order)) {

            $keyboard = [
                [
                    ["text" => "Работа с пользователем", "url" => $link]
                ]
            ];

            BotManager::bot()
                ->sendDocument(
                    $channel,
                    "#фото\n" .
                    "Идентификатор: $id\n" .
                    "Пользователь: $name\n" .
                    "Телефон: $phone\n\n" .
                    "Подпись к фото: $caption\n\n",
                    $fileToSend,
                    $thread
                );
            BotManager::bot()
                ->replyInlineKeyboard("Действия над пользователем:", $keyboard);

            BotManager::bot()
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Спасибо! Ваш файл загружен!");

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


        BotManager::bot()
            ->sendDocument(
                $channel,
                "#оплатачеком\n" .
                "Идентификатор: $id\n" .
                "Пользователь: $name\n" .
                "Телефон: $phone\n\n" .
                "Параметры заказа:\n$text\n",
                $fileToSend,
                $thread
            );

        BotManager::bot()
            ->replyInlineKeyboard("Действия над пользователем:", [
                [
                    ["text" => "📜Заказ пользователя", "url" => $historyLink]
                ],
                [
                    ["text" => "👩🏻‍💻Работа с пользователем", "url" => $link]
                ],

            ]);

        BotManager::bot()
            ->sendMessage(
                $botUser->telegram_chat_id,"Спасибо! Ваш файл загружен!");
    })
    ->fallbackPhoto(function (...$data) {
        $caption = $data[2] ?? null;
        $photos = $data[3] ?? null;

        $botUser = BotManager::bot()->currentBotUser();
        $bot = BotManager::bot()->getSelf();
        $photoToSend = $photos[count($photos) - 1]->file_id ?? null;

        $count = 0;

        if ($botUser->is_admin || $botUser->is_manager) {
            $media = \App\Models\BotMedia::query()->updateOrCreate([
                'bot_id' => $bot->id,
                'bot_user_id' => $botUser->id,
                'file_id' => $photoToSend,
            ], [
                'caption' => $caption,
                'type' => "photo"
            ]);

            $tmp = "<b>#$media->id</b> (<code>$photoToSend</code>),";
            $count++;

            BotManager::bot()
                ->reply("Фотографии ($count шт.) добавлены в медиа пространство бота с идентификаторами: $tmp - для просмотра доступных медиа используйте /media");

        }


        $caption = !is_null($caption) ? $caption : 'Без подписи';

        /*if (!str_contains($caption, "оплата")) {
            BotManager::bot()->reply("Фотография в описании должна содержать ключевое слово, например: оплата");
            return;
        }*/

        $channel = $bot->order_channel ?? $bot->main_channel ?? null;

        if (is_null($photoToSend) || is_null($channel)) {
            Log::info("Ошибка отправки фотографии!".print_r($photoToSend, true)." ".print_r($channel, true));
            BotManager::bot()->reply("Ошибка отправки фотографии!");
            return;
        }


        $name = \App\Facades\BotMethods::prepareUserName($botUser);

        $id = $botUser->telegram_chat_id;

        $phone = $botUser->phone ?? 'Не указан';

        $link = "https://t.me/$bot->bot_domain?start=" .
            base64_encode("001" . $botUser->telegram_chat_id);

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->orderBy("updated_at", "DESC")
            ->first();

        $historyLink = "https://t.me/$bot->bot_domain?start=" . (
            !is_null($order) ?
                base64_encode("001" . $botUser->telegram_chat_id . "O" . $order->id) :
                base64_encode("001" . $botUser->telegram_chat_id)
            );

        $thread = $bot->topics["orders"] ?? null;


        if (is_null($order)) {

            $keyboard = [
                [
                    ["text" => "Работа с пользователем", "url" => $link]
                ]
            ];

            BotManager::bot()
                ->sendPhoto(
                    $channel,
                    "#фото\n" .
                    "Идентификатор: $id\n" .
                    "Пользователь: $name\n" .
                    "Телефон: $phone\n\n" .
                    "Подпись к фото: $caption\n\n",
                    $photoToSend,
                    $keyboard,
                    $thread
                );

            BotManager::bot()
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Спасибо! Ваше фото загружено!");

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


        BotManager::bot()
            ->sendPhoto(
                $channel,
                "#оплатачеком\n" .
                "Идентификатор: $id\n" .
                "Пользователь: $name\n" .
                "Телефон: $phone\n\n" .
                "Параметры заказа:\n$text\n",
                $photoToSend, [
                [
                    ["text" => "📜Заказ пользователя", "url" => $historyLink]
                ],
                [
                    ["text" => "👩🏻‍💻Работа с пользователем", "url" => $link]
                ],

            ],
                $thread
            );

        BotManager::bot()
            ->sendMessage(
                $botUser->telegram_chat_id,"Спасибо! Ваше фото загружено!");
    });

BotManager::bot()
    ->location(function (...$data) {

        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        $coords = $data[1];

        \App\Facades\BusinessLogic::delivery()
            ->setBot($bot)
            ->setBotUser($botUser)
            ->storeCoordsToOrder($coords->lat ?? 0, $coords->lon ?? 0);

    });
