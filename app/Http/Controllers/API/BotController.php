<?php

namespace App\Http\Controllers\API;


use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotType;
use App\Models\BotUser;
use App\Models\Company;
use App\Models\Order;
use App\Models\ReferralHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class BotController extends Controller
{

    public function getBot(Request $request): BotResource
    {
        return new BotResource($request->bot);
    }


    public function getSelf(Request $request): BotUserResource
    {
        $botUser = BotUser::query()
            ->find($request->botUser->id);

        $orders = Order::query()
            ->where("bot_id", $request->bot->id)
            ->where("customer_id", $request->botUser->id)
            ->count();

        $refCount = BotUser::query()
            ->where("parent_id", $botUser->id)
            ->count() ?? 0;

        $botUser->order_count = $orders;
        $botUser->friends_count = $refCount;
        $botUser->parent_friend = BotUser::query()
            ->find($botUser->parent_id) ?? null;

        return new BotUserResource($botUser);
    }



    /**
     * @throws ValidationException
     */
    public function sendFeedback(Request $request): Response
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            // ->setSlug($request->slug ?? null)
            ->sendFeedback($request->all(),
                $request->hasFile('photos') ? $request->file('photos') : null);

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function sendCallback(Request $request): Response
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "message" => "required",
        ]);

        BusinessLogic::bots()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->setSlug($request->slug ?? null)
            ->sendCallback($request->all());

        return response()->noContent();
    }



}
