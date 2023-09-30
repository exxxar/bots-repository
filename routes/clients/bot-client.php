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
use App\Http\Controllers\Globals\AboutBotScriptController;
use App\Http\Controllers\Globals\BonusProductScriptController;
use App\Http\Controllers\Globals\InstagramQuestScriptController;
use App\Http\Controllers\Globals\ShopScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneScriptController;
use Illuminate\Support\Facades\Route;

Route::prefix("bot-client")
    ->group(function () {

        Route::post("/vk-auth-link", [\App\Http\Controllers\Globals\VKProductController::class, "getVKAuthLink"])
            ->middleware(["tgAuth.admin"]);

        Route::any("/vk-callback", [\App\Http\Controllers\Globals\VKProductController::class, "callback"]);

        Route::post("/send-to-channel", [BotController::class, "sendToChannel"])
            ->middleware(["tgAuth.any"]);

        Route::post("/telegram-channel-id", [BotController::class, "requestTelegramChannel"])
            ->middleware(["tgAuth.any"]);

        Route::post('/self', [BotController::class, "getSelf"])
            ->middleware(["tgAuth.any"]);

        Route::post('/bot', [BotController::class, "getBot"])
            ->middleware(["tgAuth.admin"]);


        Route::post('/callback', [BotController::class, "sendCallback"])
            ->middleware(["tgAuth.admin"]);


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
                Route::post("/company-update", "editCompany");
                Route::post("/company", "loadCompany");
                Route::post("/location-list", "loadLocations");
                Route::post("/location", "createLocation");
            });

        Route::prefix("companies")
            ->controller(CompanyController::class)
            ->middleware(["tgAuth.manager"])
            ->group(function () {
                Route::post("/manager-companies-list", "managerCompaniesList");
                Route::delete("/{companyId}", "destroy");

            });

        Route::prefix("shop")
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post("/products", [ProductController::class, "index"]);
                Route::post("/checkout", [ProductController::class, "checkout"]);
                Route::post("/products/by-ids", [ProductController::class, "getProductsByIds"]);
                Route::post("/products/random", [ProductController::class, "randomProducts"]);
                Route::post("/products/categories", [ProductController::class, "getCategories"]);
                Route::post("/products/add-product", [ProductController::class, "saveProduct"]);
                Route::post("/products/remove-all-products", [ProductController::class, "removeAllProducts"]);
                Route::delete("/products/remove-category/{categoryId}", [ProductController::class, "removeCategoryId"]);
                Route::post("/products/add-category", [ProductController::class, "addCategory"]);
                Route::post("/products/in-category", [ProductController::class, "getProductsInCategory"]);
                Route::post("/products/category/{productId}", [ProductController::class, "getCategory"]);
                Route::post("/products/{productId}", [ProductController::class, "getProduct"]);
            });

        Route::prefix("admins")
            ->controller(AdminBotController::class)
            ->group(function () {
                Route::post('/', "loadActiveAdminList")
                    ->middleware(["tgAuth.any"]);
                Route::post('/request', "requestCashBack")
                    ->middleware(["tgAuth.any"]);

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

                Route::post('/load-statistic', "statistic")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/download-bot-statistic', "exportBotStatistic")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/download-bot-users', "exportBotUsers")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/download-cashback-history', "exportCashBackHistory")
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

        Route::prefix("bot-users")
            ->controller(BotUsersController::class)
            ->group(function () {
                Route::post("/update-bot-user", "updateBotUser")
                    ->middleware(["tgAuth.admin"]);

            });

        Route::prefix("cashback")
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post('/receiver', [\App\Http\Controllers\Admin\CashBackHistoryController::class, "receiver"]);
                Route::post('/history', [\App\Http\Controllers\Admin\CashBackHistoryController::class, "index"]);
                Route::post('/add', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "addCashBack"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/remove', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "removeCashBack"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/vip', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "vipStore"])
                    ->middleware(["slug"]);
                Route::post('/user-message', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "messageToUser"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/request-user-data', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "requestUserData"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/request-refresh-menu', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "requestRefreshMenu"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/load-data', [\App\Http\Controllers\Globals\CashBackScriptController::class, "loadData"])
                ->middleware(["slug"]);
            });

        Route::prefix("cash-out")
            ->middleware(["tgAuth.any"])
            ->group(function(){
                Route::post('/withdraw-money', [\App\Http\Controllers\Globals\RequestMoneyWithdrawScriptController::class, "withDrawMoney"])
                    ->middleware(["slug"]);
            });

        Route::prefix("pages")
            ->controller(BotPageController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/page", "createPage");
                Route::post("/page-update", "updatePage");
                Route::post("/duplicate/{pageId}", "duplicate");
                Route::post("/remove/{pageId}", "destroy");
            });

        Route::prefix("bots")
            ->controller(BotController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/save-amo", [AmoCrmController::class, "saveAmoCrm"]);
                Route::post("/load-amo-fields", [AmoCrmController::class, "loadAmoFields"]);
                Route::post("/sync-amo", [AmoCrmController::class, "syncAmoCrm"]);
                Route::post("/bot-update", "updateBot");
                Route::post("/user-status", "changeUserStatus");
                Route::post("/users", "loadBotUsers");
                Route::post("/image-menu", "loadImageMenu");
                Route::post("/slugs", "loadSlugs");
                Route::post("/duplicate", "duplicate");
                Route::post("/keyboards", "loadKeyboards");
                Route::post("/keyboard-template", "createKeyboardTemplate");
                Route::post("/remove-keyboard-template/{keyboardId}", "removeKeyboardTemplate");
                Route::post("/edit-keyboard-template", "editKeyboardTemplate");
                Route::post('/switch-status',"switchBotStatus");
                Route::post('/update-shop-link',"updateShopLink");
                Route::post("/restore/{botId}", "restore");

            });

        Route::prefix("bots")
            ->controller(BotController::class)
            ->middleware(["tgAuth.manager"])
            ->group(function(){
                Route::post("/simple-bot-list", "simpleList");
                Route::post("/bot-lazy", "createBotLazy");
            });

        Route::prefix("slugs")
            ->controller(BotMenuSlugController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/global-list", "globalList");
                Route::post("/slug", "createSlug");
                Route::post("/slug-update", "updateSlug");
                Route::post("/duplicate/{slugId}", "duplicate");
                Route::get("/reload-params/{slugId}", "reloadParams");
                Route::delete("/{slugId}", "destroy");
            });

        Route::prefix("dialogs")
            ->middleware(["tgAuth.admin"])
            ->controller(BotDialogsController::class)
            ->group(function () {
                Route::post("/", "index");
            });

        Route::get("/{botDomain}", [ShopScriptController::class, "shopHomePage"])
            ->where("slug", "[0-9]+|route");

    });
