<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class InlineBotController extends Controller
{
    public function inlineHandler(...$data) {
        $inlineQueryId = $data[2] ?? null;

        Log::info(print_r($data, true));

        if (is_null($inlineQueryId))
            return;

        $button_list = [];

        $bot = BotManager::bot()->getSelf();

        $botUsers =
            \App\Models\BotUser::query()
                ->with(["user"])
                ->where("is_admin", true)
               // ->where("is_work", true)
                ->where("bot_id", $bot->id)
                ->orderBy("id", "DESC")
                ->take(8)
                ->skip(0)
                ->get();

        Log::info("step 1");

        if (!empty($botUsers))
            foreach ($botUsers as $botUser) {
                Log::info("step 2=>".$botUser->id);

                $tmp_user_id = (string)$botUser->telegram_chat_id;

                $code = base64_encode("001" . $tmp_user_id );
                $url_link = "https://t.me/" . $bot->bot_domain . "?start=$code";

                $tmp_button = [
                    'type' => 'article',
                    'id' => uniqid(),
                    'title' => "Запрос на CashBack",
                    'input_message_content' => [
                        'message_text' => sprintf("Администратор #%s %s (%s)",
                            $botUser->id,
                            ($botUser->fio_from_telegram ?? $botUser->name),
                            ($botUser->phone ?? 'Без телефона')),
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
                        ."/images/".$bot->company->slug."/".$bot->image,
                    //'url' => env("APP_URL"),
                    'description' => sprintf("Администратор #%s %s (%s)",
                        $botUser->id,
                        ($botUser->fio_from_telegram ?? $botUser->name),
                        ($botUser->phone ?? 'Без телефона')),
                    'hide_url' => false
                ];

                $button_list[] = $tmp_button;


            }

        if (empty($button_list)){
            Log::info("step 3");
            $button_list[] =  [
                'type' => 'article',
                'id' => uniqid(),
                'title' => "УПС!",
                'input_message_content' => [
                    'message_text' => "На текущий момент все администраторы офлайн!:(",
                ],

                'thumb_url' => env("APP_URL")
                    ."/images/".$bot->company->slug."/".$bot->image,
                //'url' => env("APP_URL"),
                'description' => "Сожалеем, но на текущий момент ни один администратор не вышел на работу. Возможно еще рано...",
                'hide_url' => false
            ];

        }


        BotManager::bot()
            ->sendAnswerInlineQuery($inlineQueryId, $button_list);
        //BotManager::bot()->reply("test inline");
    }
}
