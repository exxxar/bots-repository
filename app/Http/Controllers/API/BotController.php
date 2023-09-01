<?php

namespace App\Http\Controllers\API;

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
            "text" => "required",
            "inline_keyboard" => "",
            "channel" => "required",
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->sendToChannel($request->all(),
                $request->hasFile('photos') ? $request->file('photos') : null
            );
        return response()->noContent();

    }

    /**
     * @throws ValidationException
     */
    public function sendCallback(Request $request): Response
    {
        $request->validate([
            "bot_domain" => "required",
            "slug_id" => "required",
            "telegram_chat_id" => "required",
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        $bot = \App\Models\Bot::query()
            ->with(["company"])
            ->where("bot_domain", $request->bot_domain)
            ->first();

        BusinessLogic::bots()
            ->setBot($bot)
            ->sendCallback($request->all());

        return response()->noContent();
    }


    /**
     * @throws ValidationException
     */
    public function requestTelegramChannel(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "token" => "required",
            "channel" => "required",
        ]);

        $bot = Bot::query()->where("token", $request->token)->first();

        return response()
            ->json(
                BusinessLogic::bots()
                    ->setBot($bot)
                    ->requestTelegramChannel($request->all())
            );
    }


    public function loadBotUsers(Request $request): \App\Http\Resources\BotUserCollection
    {
        $request->validate([
            "botId" => "required"
        ]);

        $bot = Bot::query()->find($request->botId);

        return BusinessLogic::botUsers()
            ->setBot($bot)
            ->list();
    }


    public function loadLocations(Request $request, $companyId): \App\Http\Resources\LocationCollection
    {
        return BusinessLogic::companies()
            ->locationsList($companyId);
    }

    public function loadImageMenu(Request $request, $botId): \App\Http\Resources\ImageMenuCollection
    {
        $bot = Bot::query()
            ->with(["company", "imageMenus"])
            ->find($botId);

        return BusinessLogic::bots()
            ->setBot($bot)
            ->imageMenuList();
    }

    public function index(Request $request): \App\Http\Resources\BotCollection
    {

        return BusinessLogic::bots()
            ->list(
                $request->companyId ?? null,
                $request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }




}
