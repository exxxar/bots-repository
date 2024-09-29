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
use Illuminate\Support\Facades\Log;
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


            $model->config = $params;
            $model->save();


    }


    public function inviteFriends(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();


        function prepareContent($text, $botUser, $bot): array|string
        {
            $botDomain = $bot->bot_domain;

            $qrLink = "https://t.me/$botDomain?start=" .
                base64_encode("011" . BotManager::bot()->getCurrentChatId());

            $name = $botUser->name ?? 'Имя не указано';
            $phone = $botUser->phone ?? 'Телефон не указан';

            $friendCount = ReferralHistory::query()
                ->where("user_sender_id", $botUser->user_id)
                ->where("bot_id", $bot->id)
                ->count();

            $can_be_tags = ["b","i"];

            foreach ($can_be_tags as $tag){
                if (strpos($text, "<$tag>")>=0 && !strpos($text, "</$tag>")
                    || strpos($text, "</$tag>")>=0 && !strpos($text, "<$tag>"))
                       $text = str_replace(["<$tag>","</$tag>"], '', $text);
            }

            $qr = "<a href='https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qrLink'>QR-код</a>";

            $text = str_replace(["{{name}}"], $name ?? 'имя не указано', $text);
            $text = str_replace(["{{phone}}"], $phone ?? 'телефон не указан', $text);
            $text = str_replace(["{{qr}}"], $qr, $text);
            $text = str_replace(["{{qrLink}}"], $qrLink, $text);
            $text = str_replace(["{{friendsCount}}"], $friendCount ?? '0', $text);
            $text = str_replace(["%s"], $friendCount ?? '0', $text);
            return str_replace(["{{username}}"], "@" . ($username ?? 'имя не указано'), $text);

        }

        $mainText = ((Collection::make($config[1])
                ->where("key", "main_text")
                ->first())["value"] ?? "Вы пригласили <b>{{friendsCount}} друзей</b>\n Вы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой");

        $referralText = ((Collection::make($config[1])
                ->where("key", "referral_text")
                ->first())["value"] ?? "Вы пригласили <b>{{friendsCount}} друзей</b>\n Вы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой") . "\n\n{{qrLink}}";


        $imgPath = (Collection::make($config[1])
            ->where("key", "image_main")
            ->first())["value"] ?? null;

        $imgPath = is_null($imgPath) ? public_path() . "/images/cashman.jpg" :
            $imgPath;


        Log::info("text in friends script:$mainText");
        \App\Facades\BotManager::bot()
            ->reply(prepareContent($mainText, $botUser, $bot));

        $message = prepareContent($referralText, $botUser, $bot);

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
