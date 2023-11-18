<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\InlineBotController;
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
    ->route("/remove_media_file ([0-9]+)", "removeMediaFile")
    ->route("/remove_notes ([0-9]+)", "removeNotes")
    ->route("/show_media_file ([0-9]+)", "showMediaFile")
    ->route("/cashman", "cashmanPayment")
    ->route("/start ([0-9a-zA-Z=]+)", "startWithParam")
    ->route("/diagnostic ([0-9]+)", "getDiagnosticTable");


BotManager::bot()
    ->controller(InlineBotController::class)
    ->inline("inlineHandler");

BotManager::bot()
    ->fallbackPhoto(function (...$data) {
        $caption = $data[2] ?? null;
        $photos = $data[3] ?? null;

        $botUser = BotManager::bot()->currentBotUser();
        $bot = BotManager::bot()->getSelf();
        $photoToSend = $photos[count($photos) - 1]->file_id ?? null;

        Log::info("fallback photo");
        if ($botUser->is_admin || $botUser->is_manager){
            $media = \App\Models\BotMedia::query()->updateOrCreate([
                'bot_id' => $bot->id,
                'bot_user_id' => $botUser->id,
                'file_id' => $photoToSend,
            ], [
                'caption' => $caption,
                'type' => "photo"
            ]);
            BotManager::bot()
                ->reply("Фотография добавлена в медиа пространство бота с идентификатором: <b>#$media->id</b> - для просмотра доступных медиа используйте /media");

        }

        if (is_null($caption)) {
            BotManager::bot()->reply("Фотография в описании должна содержать подпись!");
            return;
        }

        $caption = mb_strtolower($caption);

        if (!str_contains($caption, "оплата")) {
            BotManager::bot()->reply("Фотография в описании должна содержать ключевое слово, например: оплата");
            return;
        }




        $channel = $bot->order_channel ?? $bot->main_channel ?? null;

        if (is_null($photoToSend) || is_null($channel)) {
            BotManager::bot()->reply("Ошибка отправки фотографии!");
            return;
        }


        $name = \App\Facades\BotMethods::prepareUserName($botUser);

        $id = $botUser->telegram_chat_id;

        $phone = $botUser->phone ?? 'Не указан';

        $data = "001" . $botUser->telegram_chat_id;

        $link = "https://t.me/$bot->bot_domain?start=" .
            base64_encode($data);

        $thread = $bot->topics["orders"] ?? null;

        BotManager::bot()
            ->sendPhoto(
                $channel,
                "#оплатачеком\n" .
                "Идентификатор: $id\n" .
                "Пользователь: $name\n" .
                "Телефон: $phone\n",
                $photoToSend, [
                    [
                        ["text" => "Работа с пользователем", "url" => $link]
                    ]
                ],
                $thread
            );

        BotManager::bot()->reply("Спасибо! Ваше фото загружено!");
    });

BotManager::bot()
    ->fallbackVideo(function (...$data) {
        $caption = $data[2] ?? null;
        $video = $data[3] ?? null;
        $type = $data[4] ?? "video";

        $botUser = BotManager::bot()->currentBotUser();


        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()->reply("Данная опция доступна только персоналу бота!");
            return;
        }

        $videoToSend = $video->file_id ?? null;

        $bot = BotManager::bot()->getSelf();

        $media = \App\Models\BotMedia::query()->updateOrCreate([
            'bot_id' => $bot->id,
            'bot_user_id' => $botUser->id,
            'file_id' => $videoToSend,
        ], [
            'caption' => $caption,
            'type' => $type
        ]);

        BotManager::bot()
            ->reply("Видео добавлено в медиа пространство бота с идентификатором: <b>#$media->id</b>\n<em>$videoToSend</em>\nдля просмотра доступных медиа используйте /media ");

    });
