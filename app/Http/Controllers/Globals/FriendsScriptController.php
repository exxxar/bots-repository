<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\ReferralHistory;
use Illuminate\Support\Collection;
use Telegram\Bot\FileUpload\InputFile;

class FriendsScriptController extends Controller
{
    const SCRIPT = "global_friends_main";
/*
    const KEY_MAX_ATTEMPTS = "max_attempts";
    const KEY_CALLBACK_CHANNEL_ID = "callback_channel_id";
    const KEY_RULES_TEXT = "rules_text";
    const KEY_RESULT_MESSAGE = "result_message";

    const KEY_BTN_TEXT = "btn_text";*/
    const KEY_MAIN_TEXT = "main_text";
    const KEY_REFERRAL_TEXT = "referral_text";
    const KEY_IMAGE_MAIN = "image_main";

    public function inviteFriends(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $botDomain = $bot->bot_domain;

        $companyDomain = $bot->company->slug;

        $mainText = (Collection::make($config[1])
            ->where("key", self::KEY_MAIN_TEXT)
            ->first())["value"] ?? "Вы пригласили <b>%s друзей</b>\nВы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой";

        $referralText = (Collection::make($config[1])
            ->where("key", self::KEY_REFERRAL_TEXT)
            ->first())["value"] ?? "Перешли эту ссылку друзьям:\n<a href=\"%s\">%s</a>\n<span class=\"tg-spoiler\">И получи бонусные баллы <strong>CashBack</strong></span>";

        $imgPath = (Collection::make($config[1])
            ->where("key", self::KEY_IMAGE_MAIN)
            ->first())["value"] ??  null;

        $imgPath = is_null($imgPath)? storage_path("app/public") . "/companies/$companyDomain/" . ($bot->image ?? 'noimage.jpg') :
            $imgPath;

        $qr = "https://t.me/$botDomain?start=" .
            base64_encode("001" . BotManager::bot()->getCurrentChatId());

        $friendCount = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->count();

        \App\Facades\BotManager::bot()
            ->replyPhoto( sprintf($mainText,$friendCount),
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));



        $file = InputFile::create(
            $imgPath
            /*file_exists($imgPath) ?
                $imgPath :
                public_path() . "/images/cashman.jpg"*/
        );

        \App\Facades\BotManager::bot()
            ->replyPhoto(sprintf($referralText, $qr, $qr),
                $file
            );

        BotManager::bot()
            ->sendReplyMenu("Пригласить друзей",
                "menu_level_2_restaurant_5");
    }
}
