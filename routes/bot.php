<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\InlineBotController;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
    ->route("/create_topics_in_channel", "createTopics")
    ->route("/save_as_main_channel", "saveAsMainChannel")
    ->route("/save_as_order_channel", "saveAsOrderChannel")
    ->route("/about", "aboutBot")
    ->route("/help", "helpBot")
    ->route("/upload", "uploadFilesToBot")
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
    //->fallbackDocument("uploadAnyKindOfMedia")
    ->fallbackAudio("uploadAnyKindOfMedia")
    ->fallbackSticker("uploadAnyKindOfMedia")
    ->fallbackVideo("uploadAnyKindOfMedia");


BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

BotManager::bot()
    ->fallbackDocument(function (...$data) {

        $caption = $data[2] ?? null;
        $files = $data[3] ?? null;

        $botUser = BotManager::bot()->currentBotUser();
        $bot = BotManager::bot()->getSelf();
        $fileToSend = is_array($files) ? $files[count($files) - 1]->file_id ?? null : $files->file_id;

        $count = 0;

        if ($botUser->is_admin || $botUser->is_manager) {
            $media = \App\Models\BotMedia::query()->updateOrCreate([
                'bot_id' => $bot->id,
                'bot_user_id' => $botUser->id,
                'file_id' => $fileToSend,
            ], [
                'caption' => $caption,
                'type' => "document"
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
                    "#документ\n" .
                    "Идентификатор: $id\n" .
                    "Пользователь: $name\n" .
                    "Телефон: $phone\n\n" .
                    "Подпись к документу: $caption\n\n",
                    $fileToSend,
                    $thread
                );
            BotManager::bot()
                ->sendInlineKeyboard($channel, "Действия над пользователем:", $keyboard, $thread);

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

        $keyboard = [
            [
                ["text" => "📜Заказ пользователя", "url" => $historyLink]
            ],
            [
                ["text" => "👩🏻‍💻Работа с пользователем", "url" => $link]
            ],

        ];

        BotManager::bot()
            ->sendInlineKeyboard($channel, "Действия над пользователем:", $keyboard, $thread);


        BotManager::bot()
            ->sendMessage(
                $botUser->telegram_chat_id, "Спасибо! Ваш файл загружен!");
    })
    ->fallbackPhoto(function (...$data) {
        $caption = $data[2] ?? null;
        $photos = $data[3] ?? null;

        $botUser = BotManager::bot()->currentBotUser();
        $bot = BotManager::bot()->getSelf();
        $photoToSend = $photos[count($photos) - 1]->file_id ?? null;

        $caption = !is_null($caption) ? $caption : null;

        $channel = $bot->order_channel ?? $bot->main_channel ?? null;

        if (is_null($photoToSend) || is_null($channel)) {
            BotManager::bot()->reply("Ошибка отправки фотографии!");
            return;
        }

        $name = \App\Facades\BotMethods::prepareUserName($botUser);

        $chatId = $botUser->telegram_chat_id;

        $filename = "images-$chatId.json";
        $folder = "telegram-images";
        $filePath = "$folder/$filename";

        $botDomain = $bot->bot_domain;

        $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $chatId);

        if (Storage::exists($filePath)) {
            $config = json_decode(Storage::get($filePath), true);

            BotManager::bot()
                ->sendChatAction(
                    $botUser->telegram_chat_id,
                    "upload_photo");
        } else {
            $config = [
                'bot_id' => $bot->id,
                'link' => $link,
                'channel' => $bot->order_channel ?? null,
                'thread' => $bot->topics["response"] ?? null,
                'user' => [
                    'name' => $name,
                    'telegram_chat_id' => $chatId,
                ],
                'images' => [],
                'messages' => [],

            ];

            BotManager::bot()
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Ваши фотографии будут отправлены администратору в течении 10 минут!");

        }

        // Добавляем file_id
        $config['images'][] = $photoToSend;
        if (!is_null($caption))
            $config['messages'][] = $caption;

        Storage::put($filePath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if ($botUser->is_admin || $botUser->is_manager) {
            $media = \App\Models\BotMedia::query()->updateOrCreate([
                'bot_id' => $bot->id,
                'bot_user_id' => $botUser->id,
                'file_id' => $photoToSend,
            ], [
                'caption' => $caption ?? 'Без подписи',
                'type' => "photo"
            ]);

            $tmp = "<b>#$media->id</b> (<code>$photoToSend</code>),";

            BotManager::bot()
                ->reply("Фотография добавлена в медиа пространство бота с идентификаторами: $tmp - для просмотра доступных медиа используйте /media");
        }

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->whereNull("payed_at")
            ->orderBy("updated_at", "DESC")
            ->first();

        if (!is_null($order)) {
            $phone = $botUser->phone ?? 'Не указан';

            $historyLink = "https://t.me/$bot->bot_domain?start=" . base64_encode("001" . $botUser->telegram_chat_id . "O" . $order->id);

            $thread = $bot->topics["orders"] ?? null;

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

                $order->payed_at = Carbon::now();
                $order->save();

                

            } else {
                $order->delete();
                return;
            }

            $text = "Заказ #$order->id\nПрислан из $from:\n<em>$products</em>\nДата заказа: " . Carbon::parse($order->created_at)
                    ->format("Y-m-d H:i:s");

            BotManager::bot()
                ->sendPhoto(
                    $channel,
                    "#оплатачеком\n" .
                    "Идентификатор: $chatId\n" .
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

        }

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
