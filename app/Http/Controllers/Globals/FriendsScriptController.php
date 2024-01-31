<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\ReferralHistory;
use Illuminate\Support\Collection;
use Telegram\Bot\FileUpload\InputFile;

class FriendsScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_friends_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Мои друзья",
                'comment' => "Реферальная программа",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Вы пригласили <b>%s друзей</b>\nВы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой"
            ],
            [
                "type" => "image",
                "key" => "image_main",
                "value" => null,

            ],
            [
                "type" => "text",
                "key" => "referral_text",
                "value" => "Перешли эту ссылку друзьям:\n<a href=\"%s\">%s</a>\n<span class=\"tg-spoiler\">И получи бонусные баллы <strong>CashBack</strong></span>",

            ]
        ];

        if (count($model->config ?? []) != count($params)) {
            $model->config = $params;
            $model->save();
        }

    }

    public function inviteFriends(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $botDomain = $bot->bot_domain;

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Вы пригласили <b>%s друзей</b>\nВы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой";

        $referralText = (Collection::make($config[1])
            ->where("key", "referral_text")
            ->first())["value"] ?? "Вы пригласили <b>%s друзей</b>\nВы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой";


        $imgPath = (Collection::make($config[1])
            ->where("key", "image_main")
            ->first())["value"] ?? null;

        $imgPath = is_null($imgPath) ? env("APP_URL") . "/images/cashman.jpg" :
            $imgPath;

        $qr = "https://t.me/$botDomain?start=" .
            base64_encode("011" . BotManager::bot()->getCurrentChatId());

        $friendCount = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->count();

        \App\Facades\BotManager::bot()
            ->replyPhoto(sprintf($mainText, $friendCount),
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));

        try {
            $message = sprintf($referralText, $qr, $qr);
        } catch (\Exception $e) {
            $message = "Упс, у вас что-то с параметрами сообщения";
        }

        if (is_null($imgPath))
            \App\Facades\BotManager::bot()
                ->reply($message);
        else
            \App\Facades\BotManager::bot()
                ->replyPhoto($message,
                    str_contains($imgPath, "http") ? InputFile::create($imgPath) : $imgPath
                );


    }
}
