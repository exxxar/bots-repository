<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\PromoCodeCollection;
use App\Http\Resources\PromoCodeResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class PromocodeScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_promocode_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Ввод промокода",
                'comment' => "Позволяет пользователю вводить промокод и получать бонусы \ скидки",
            ]);

        $params = [

            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Вы можете ввести промокод и получить скидку",

            ],

            [
                "type" => "image",
                "key" => "image",
                "value" => null,
            ],
            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Ввести промокод",

            ],

        ];

        $model->config = $params;
        $model->save();

    }

    public function list(Request $request): PromoCodeCollection
    {

        $bot = $request->bot ?? null;

        return BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->listOfPromoCodes(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }

    public function remove(Request $request, $promoCodeId): PromoCodeResource
    {
        return BusinessLogic::promoCodes()
            ->removePromoCode($promoCodeId);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): PromoCodeResource
    {
        $request->validate([
            'code' => "required",
        ]);

        $bot = $request->bot ?? null;

        return BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->store($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function activate(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            // "bot_id" => "required",
            "code" => "required",
        ]);

        $botUser = $request->botUser;

        $bot = $request->bot;

        BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->activatePromoCode(
                $request->all()
            );

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function activateShopDiscount(Request $request){
        $request->validate([
            // "bot_id" => "required",
            "code" => "required",
        ]);

        $botUser = $request->botUser;

        $bot = $request->bot;

       return BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->activatePromoCodeForDiscount(
                $request->all()
            );


    }

    public function promocodeScriptRun(...$config)
    {
        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $image = (Collection::make($config[1])
            ->where("key", "image")
            ->first())["value"] ?? null;

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Начисление бонусов за промокод";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "Ввести промокод";

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();


        $keyboard = [
            [
                ["text" => "$btnText", "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId#/s/new-promo-code"
                ]],
            ],

        ];

        if (is_null($image))
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard("$mainText", $keyboard);
        else
            \App\Facades\BotManager::bot()
                ->replyPhoto("$mainText", $image, $keyboard);


    }
}
