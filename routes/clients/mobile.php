<?php

use App\Http\Controllers\Bots\Web\AdminBotController;
use App\Http\Controllers\Bots\Web\AmoCrmController;
use App\Http\Controllers\Bots\Web\BotController;
use App\Http\Controllers\Bots\Web\BotDialogsController;
use App\Http\Controllers\Bots\Web\BotMenuSlugController;
use App\Http\Controllers\Bots\Web\BotPageController;
use App\Http\Controllers\Bots\Web\BotUsersController;
use App\Http\Controllers\Bots\Web\CompanyController;
use App\Http\Controllers\Bots\Web\ProductController;
use App\Http\Controllers\Bots\Web\QueueController;
use App\Http\Controllers\Bots\Web\YClientsController;
use App\Http\Controllers\Globals\AboutBotScriptController;
use App\Http\Controllers\Globals\BonusProductScriptController;
use App\Http\Controllers\Globals\InstagramQuestScriptController;
use App\Http\Controllers\Globals\ProfileFormScriptController;
use App\Http\Controllers\Globals\ShopScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneCustomScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneScriptController;
use App\Http\Controllers\MobileController;
use App\Http\Resources\BotSecurityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;


Route::prefix("s")
    ->group(function () {

        Route::prefix("pages")
            ->middleware(["mobile"])
            ->group(function () {
                Route::post("{pageId}", function (Request $request, $pageId) {

                    $bot = $request->bot ?? null;
                    $page = \App\Models\BotPage::query()
                        ->where("bot_id", $bot->id)
                        ->where("id", $pageId)
                        ->first();

                    return new \App\Http\Resources\BotPageResource($page);

                });
            });

        Route::get("/{botDomain}", function ($botDomain) {
            $bot = \App\Models\Bot::query()
                ->with(["company"])
                ->where("bot_domain", $botDomain)
                ->first();

            Session::put("domain", $botDomain);
            Inertia::setRootView("mobile");

            return Inertia::render('Main', [
                'bot' => BotSecurityResource::make($bot),
                'theme' => $bot->settings["theme"] ?? null
            ]);
        });
    });
