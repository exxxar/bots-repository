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

class GeoScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_geo_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Гео-метка",
                'comment' => "Скрипт отображения карты с координатой",
            ]);

        $params = [

            [
                "type" => "geo",
                "key" => "coords",
                "value" => "00.000000,00.000000",

            ],

        ];
        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }

    }


    public function geoScript(...$config)
    {
        $coords = (Collection::make($config[1])
            ->where("key", "coords")
            ->first())["value"] ?? "00.000000,00.000000";

        $latitude = explode(',', $coords)[0] ?? 0;
        $longitude = explode(',', $coords)[1] ?? 0;

        BotManager::bot()
            ->replyLocation($latitude, $longitude);

    }
}
