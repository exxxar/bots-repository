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
        $hasMainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_friends_main")
            ->first();


        if (is_null($hasMainScript))
            return;

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_friends_main",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Мои друзья",
                'comment' => "Реферальная программа",
            ]);

        if (is_null($model->config)) {
            $model->config = [
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
            base64_encode("001" . BotManager::bot()->getCurrentChatId());

        $friendCount = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->count();

        \App\Facades\BotManager::bot()
            ->replyPhoto(sprintf($mainText, $friendCount),
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));


        \App\Facades\BotManager::bot()
            ->replyPhoto(sprintf($referralText, $qr, $qr),
                InputFile::create($imgPath)
            );


    }
}
