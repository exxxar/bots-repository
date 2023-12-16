<?php

namespace App\Http\Controllers\Landing;

use App\Classes\SystemUtilitiesTrait;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Models\Bot;
use App\Models\BotType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class BotController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function sendToChannel(Request $request): Response
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);


        $bot = Bot::query()
            ->where("bot_domain", "nextitgroup_bot")
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->sendLandingRequest($request->all());

        return response()->noContent();

    }


    public function simpleList(Request $request): \App\Http\Resources\BotSecurityCollection
    {
        return BusinessLogic::bots()
            ->landingCollections(
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }



}
