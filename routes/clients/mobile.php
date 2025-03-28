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
use Illuminate\Support\Facades\Route;


Route::prefix("mobile")
    ->group(function () {

    /*    Route::get("/{domain}/guest", [MobileController::class, "guestMobileHomePage"])
            ->name("mobile.guest");*/

        Route::get("/{botDomain}/{page}", [MobileController::class, "mobileHomePage"])
            //->middleware(["mobile.auth:mobile.guest"])
            ->name("mobile.base");

    });
