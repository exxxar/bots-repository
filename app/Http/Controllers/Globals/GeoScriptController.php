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
        $mainScript = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_geo_main")
            ->first();

        if (is_null($mainScript))
            return;

        if (empty($mainScript->config ?? [])) {
            $mainScript->config = [

                [
                    "type" => "geo",
                    "key" => "coords",
                    "value" => "00.000000,00.000000",

                ],

            ];
            $mainScript->save();
        }

        BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_geo_main",
                "bot_id" => $bot->id,
                'is_global' => true,
            ],
            [
                'command' => ".*Гео-метка",
                'comment' => "Скрипт отображения карты с координатой",
            ]);

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
