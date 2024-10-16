<?php

namespace App\Classes;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\ReferralHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class SlugController extends Controller
{
    //protected $bot;

    public function __construct()
    {

        //$this->bot = BotManager::bot()->getSelf();

    }

    protected abstract function config(Bot $bot);

    protected function prepareContent($text, $botUser, $bot): array|string
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

        $can_be_tags = ["b", "i"];

        foreach ($can_be_tags as $tag) {
            if (strpos($text, "<$tag>") >= 0 && !strpos($text, "</$tag>")
                || strpos($text, "</$tag>") >= 0 && !strpos($text, "<$tag>"))
                $text = str_replace(["<$tag>", "</$tag>"], '', $text);
        }

        $qr = "<a href='https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qrLink'>QR-код</a>";

        $text = str_replace(["{{name}}"], $name ?? 'имя не указано', $text);
        $text = str_replace(["{{phone}}"], $phone ?? 'телефон не указан', $text);
        $text = str_replace(["{{qr}}"], $qr, $text);
        $text = str_replace(["{{qrLink}}"], $qrLink, $text);
        $text = str_replace(["{{friendsCount}}"], $friendCount ?? '0', $text);
        $text = str_replace(["{{cashBack}}"], '0', $text);
        $text = str_replace(["%s"], $friendCount ?? '0', $text);
        return str_replace(["{{username}}"], "@" . ($username ?? 'имя не указано'), $text);

    }


}
