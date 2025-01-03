<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\ReferralHistory;
use Illuminate\Http\Request;
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

    public function loadScriptVariants(Request $request){

        $bot = $request->bot ?? null;

        //SELECT * FROM `bot_menu_slugs` WHERE (`slug`="global_friends_main" AND `bot_id`=21) OR `parent_slug_id`=1531 OR `parent_slug_id`=1574
        $globalScripts = BotMenuSlug::query()
            ->where("slug","global_friends_main")
            //->where("bot_id",$bot->id)
            ->get();

        $ids = $globalScripts->pluck("id");

        $parentScripts = BotMenuSlug::query()
            ->where("bot_id",$bot->id)
            ->whereIn("parent_slug_id", $ids)
            ->get();

        $baseScripts =  BotMenuSlug::query()
            ->where("slug","global_friends_main")
            ->where("bot_id",$bot->id)
            ->get();

        return [...$baseScripts->toArray(), ...$parentScripts->toArray()];
    }



    public function inviteFriends(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

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


        \App\Facades\BotManager::bot()
            ->reply($this->prepareContent($mainText, $botUser, $bot));

        $message = $this->prepareContent($referralText, $botUser, $bot);

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
