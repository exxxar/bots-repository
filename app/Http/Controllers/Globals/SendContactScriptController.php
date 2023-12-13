<?php

namespace App\Http\Controllers\Globals;

use App\Classes\BotMethods;
use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class SendContactScriptController extends SlugController
{
    public function config(Bot $bot)
    {


        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_send_contact_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Отправить контакт",
                'comment' => "Скрипт позволяющий отправить телефонный контакт пользовалю",
            ]);


        $params = [

            [
                "type" => "phone",
                "key" => "phone",
                "value" => "+7(000)000-00-00",

            ],
            [
                "type" => "text",
                "key" => "first_name",
                "value" => "Имя",

            ],
            [
                "type" => "text",
                "key" => "last_name",
                "value" => "Фамилия",

            ],
        ];

        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }


    }


    public function sendContactScript(...$config)
    {
        Log::info("sendContactScript".print_r($config, true));
        $phone = (Collection::make($config[1])
            ->where("key", "phone")
            ->first())["value"] ?? "+7(000)000-00-00";

        $firstName = (Collection::make($config[1])
            ->where("key", "first_name")
            ->first())["value"] ?? "Имя";

        $lastName = (Collection::make($config[1])
            ->where("key", "last_name")
            ->first())["value"] ?? "Фамилия";

        BotManager::bot()
            ->replyContact($phone, $firstName, $lastName);

    }
}
