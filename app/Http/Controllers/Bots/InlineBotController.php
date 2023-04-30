<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;

class InlineBotController extends Controller
{
    public function inlineHandler(...$data) {
        $inlineQueryId = $data[1] ?? null;

        if (is_null($inlineQueryId))
            return;

        $button_list = [];

        $bot = BotManager::bot()->getSelf();

        $botUsers =
            \App\Models\BotUser::query()
                ->with(["user"])
                ->where("is_admin", true)
                ->where("is_work", true)
                ->where("bot_id", $bot->id)
                ->orderBy("id", "DESC")
                ->take(8)
                ->skip(0)
                ->get();

        if (!empty($botUsers))
            foreach ($botUsers as $botUser) {

                $tmp_user_id = (string)$botUser->user->telegram_chat_id;

                $code = base64_encode("001" . $tmp_user_id );
                $url_link = "https://t.me/" . $bot->bot_domain . "?start=$code";

                $tmp_button = [
                    'type' => 'article',
                    'id' => uniqid(),
                    'title' => "Запрос на CashBack",
                    'input_message_content' => [
                        'message_text' => sprintf("Администратор #%s %s (%s)",
                            $botUser->user->id,
                            ($botUser->user->fio_from_telegram ?? $botUser->user->name),
                            ($botUser->user->phone ?? 'Без телефона')),
                    ],
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                ['text' => "\xF0\x9F\x91\x89Запросить CashBack у администратора",
                                    "url" => "$url_link"],
                            ],

                        ]
                    ],
                    'thumb_url' => env("APP_URL")
                        ."/images/".$bot->bot_domain."/".$bot->image,
                    //'url' => env("APP_URL"),
                    'description' => sprintf("Администратор #%s %s (%s)",
                        $botUser->user->id,
                        ($botUser->user->fio_from_telegram ?? $botUser->user->name),
                        ($botUser->user->phone ?? 'Без телефона')),
                    'hide_url' => false
                ];

                $button_list[] = $tmp_button;


            }


        BotManager::bot()
            ->sendAnswerInlineQuery($inlineQueryId, $button_list);
        //BotManager::bot()->reply("test inline");
    }
}
