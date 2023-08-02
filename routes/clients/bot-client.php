<?php
use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Bots\AdminBotController;
use App\Http\Controllers\Globals\InstagramQuestScriptController;
use App\Http\Controllers\Globals\ShopScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneScriptController;

use Illuminate\Support\Facades\Route;

Route::prefix("bot-client")
    ->group(function () {

        Route::post('/self', [BotController::class, "getSelf"]);
        Route::post('/callback', [BotController::class, "sendCallback"]);

        Route::prefix("admin")
            ->controller(AdminBotController::class)
            ->group(function () {
                Route::post('/load-statistic/{botDomain}', "statistic");

            });

        Route::prefix("{scriptId}/wheel-of-fortune")
            ->controller(WheelOfFortuneScriptController::class)
            ->group(function () {
                Route::post('/prepare/{botDomain}', "formWheelOfFortunePrepare");
                Route::get('/load-data/{botDomain}', "loadData");
                //  Route::get('/{botDomain}/{path?}', "formWheelOfFortune");
                Route::post('/{botDomain}', "formWheelOfFortuneCallback");
            });

        Route::prefix("about-bot")
            ->controller(\App\Http\Controllers\Globals\AboutBotScriptController::class)
            ->group(function () {
                Route::get('/callback/{botDomain}', "callbackFormGet");
                Route::post('/callback/{botDomain}', "callbackFormPost");
            });

        Route::prefix("{scriptId}/instagram-quest")
            ->controller(InstagramQuestScriptController::class)
            ->group(function () {
                Route::get('/load-data/{botDomain}', "loadData");
                Route::post('/prepare/{botDomain}', "instagramQuestPrepare");
                //Route::get('/{botDomain}/{path?}', "instagramQuestForm");
                Route::post('/{botDomain}', "instagramQuestCallback");
            });

        Route::prefix("shop")
            ->group(function () {
                Route::post("/products", [\App\Http\Controllers\Admin\ProductController::class, "index"]);
                Route::post("/products-by-ids", [\App\Http\Controllers\Admin\ProductController::class, "getProductsByIds"]);
                Route::get("/products/{productId}", [\App\Http\Controllers\Admin\ProductController::class, "getProduct"]);
                Route::post("/random-products", [\App\Http\Controllers\Admin\ProductController::class, "randomProducts"]);
            });

        Route::prefix("admins")
            ->controller(AdminBotController::class)
            ->group(function () {
                Route::post('/', "loadActiveAdminList");
                Route::post('/request', "requestCashBack");
                Route::post('/send-invoice', "sendInvoice")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/add', "addAdmin")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/send-approve', "sendApprove")
                    ->middleware(["tgAuth.admin"]);

                Route::post('/remove', "removeAdmin")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/self-remove', "selfRemoveAdmin")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/work-status', "workStatus")
                    ->middleware(["tgAuth.admin"]);
            });

        Route::prefix("actions")
            ->controller(\App\Http\Controllers\Admin\BotUsersController::class)
            ->group(function () {
                Route::post("/history", "loadActionStatusHistories")
                    ->middleware(["tgAuth.admin"]);

            });

        Route::prefix("users")
            ->controller(\App\Http\Controllers\Admin\BotUsersController::class)
            ->group(function () {
                Route::post("/search", "loadBotUsers")
                    ->middleware(["tgAuth.admin"]);

            });

        Route::prefix("cashback")
            ->group(function () {
                Route::post('/receiver', [\App\Http\Controllers\Admin\CashBackHistoryController::class, "receiver"])
                    ->middleware(["tgAuth.any"]);
                Route::post('/history', [\App\Http\Controllers\Admin\CashBackHistoryController::class, "index"])
                    ->middleware(["tgAuth.any"]);
                Route::post('/add', [\App\Http\Controllers\Bots\AdminBotController::class, "addCashBack"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/remove', [\App\Http\Controllers\Bots\AdminBotController::class, "removeCashBack"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/vip', [\App\Http\Controllers\Bots\AdminBotController::class, "vipStore"]);
                //Route::post('/deliveryman', [\App\Http\Controllers\Bots\AdminBotController::class, "deliverymanStore"]);
                Route::post('/user-in-location', [\App\Http\Controllers\Bots\AdminBotController::class, "acceptUserInLocation"])
                    ->middleware(["tgAuth.admin"]);
            });

        Route::get("{scriptId}/interface/{botDomain}/{path?}", [ShopScriptController::class, "shopHomePage"])
            ->where("scriptId", "[0-9]+|route");


    });
