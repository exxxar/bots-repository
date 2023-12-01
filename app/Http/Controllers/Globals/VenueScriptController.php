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

class VenueScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_venue_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Место проведения события",
                'comment' => "Скрипт отображения карты с координатами, адресом и заголовком",
            ]);

        $params = [

            [
                "type" => "geo",
                "key" => "coords",
                "value" => "00.000000,00.000000",

            ],
            [
                "type" => "text",
                "key" => "title",
                "value" => "Заголовок",

            ],
            [
                "type" => "text",
                "key" => "address",
                "value" => "Адрес расположения",

            ],

        ];

        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }

    }


    public function venueScript(...$config)
    {
        $coords = (Collection::make($config[1])
            ->where("key", "coords")
            ->first())["value"] ?? "00.000000,00.000000";

        $latitude = explode(',', $coords)[0] ?? 0;
        $longitude = explode(',', $coords)[1] ?? 0;

        $address = (Collection::make($config[1])
            ->where("key", "address")
            ->first())["value"] ?? "ул. Красная";

        $title= (Collection::make($config[1])
            ->where("key", "title")
            ->first())["value"] ?? "Точка сбора";

        BotManager::bot()
            ->replyVenue($latitude, $longitude, $address, $title);

    }
}
