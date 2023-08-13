<?php
use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Bots\ProductController;
use App\Http\Controllers\Bots\AdminBotController;
use App\Http\Controllers\Bots\CompanyController;
use App\Http\Controllers\Globals\AboutBotScriptController;
use App\Http\Controllers\Globals\BonusProductScriptController;
use App\Http\Controllers\Globals\InstagramQuestScriptController;
use App\Http\Controllers\Globals\ShopScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneScriptController;

use Illuminate\Support\Facades\Route;

Route::prefix("bot-client")
    ->group(function () {

        Route::post('/self', [BotController::class, "getSelf"])
            ->middleware(["tgAuth.any"]);

        Route::post('/bot', [BotController::class, "getBot"])
            ->middleware(["tgAuth.admin"]);


        Route::post('/callback', [BotController::class, "sendCallback"]);

        Route::prefix("admin")
            ->controller(AdminBotController::class)
            ->group(function () {
                Route::post('/load-statistic/{botDomain}', "statistic");
            });

        Route::prefix("wheel-of-fortune")
            ->controller(WheelOfFortuneScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/prepare', "formWheelOfFortunePrepare");
                Route::post('/load-data', "loadData");
                Route::post('/callback', "formWheelOfFortuneCallback");
            });

        Route::prefix("bonus-product")
            ->controller(BonusProductScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/prepare', "prepare");
                Route::post('/check', "check")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/exchange', "exchange")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/load-action-data', "loadActionData")
                    ->middleware(["tgAuth.admin"]);
            });

        Route::prefix("instagram-quest")
            ->controller(InstagramQuestScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/load-data', "loadData");
                Route::post('/prepare', "instagramQuestPrepare");
                Route::post('/callback', "instagramQuestCallback");
            });

        Route::prefix("about-bot")
            ->controller(AboutBotScriptController::class)
            ->group(function () {
                Route::get('/callback/{botDomain}', "callbackFormGet");
                Route::post('/callback/{botDomain}', "callbackFormPost");
            });

        Route::prefix("companies")
            ->controller(CompanyController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/company-update","editCompany");
                Route::post("/company", "loadCompany");
                Route::post("/location-list", "loadLocations");
                Route::post("/location", "createLocation");
            });

        Route::prefix("shop")
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post("/products", [ProductController::class, "index"]);
                Route::post("/checkout", [ProductController::class, "checkout"]);
                Route::post("/products/by-ids", [ProductController::class, "getProductsByIds"]);
                Route::post("/products/random", [ProductController::class, "randomProducts"]);
                Route::post("/products/categories", [ProductController::class, "getCategories"]);
                Route::post("/products/in-category", [ProductController::class, "getProductsInCategory"]);
                Route::post("/products/category/{productId}", [ProductController::class, "getCategory"]);
                Route::post("/products/{productId}", [ProductController::class, "getProduct"]);
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
                Route::post('/vip', [\App\Http\Controllers\Bots\AdminBotController::class, "vipStore"])
                    ->middleware(["tgAuth.any"]);
                //Route::post('/deliveryman', [\App\Http\Controllers\Bots\AdminBotController::class, "deliverymanStore"]);
                Route::post('/user-in-location', [\App\Http\Controllers\Bots\AdminBotController::class, "acceptUserInLocation"])
                    ->middleware(["tgAuth.admin"]);

                Route::post('/request-user-data', [\App\Http\Controllers\Bots\AdminBotController::class, "requestUserData"])
                    ->middleware(["tgAuth.admin"]);

                Route::post('/request-refresh-menu', [\App\Http\Controllers\Bots\AdminBotController::class, "requestRefreshMenu"])
                    ->middleware(["tgAuth.admin"]);



            });

        Route::get("/{botDomain}", [ShopScriptController::class, "shopHomePage"])
            ->where("slug", "[0-9]+|route");

    });
