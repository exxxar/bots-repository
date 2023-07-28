<?php

use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Admin\BotPageController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Bots\AdminBotController;
use App\Http\Controllers\Globals\InstagramQuestScriptController;
use App\Http\Controllers\Globals\ShopScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneScriptController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post("/test-auth/{botDomain}", [ShopScriptController::class,"shopTestCallback"]);



Route::get("/test-amo", function () {
    $amo = new \App\Integrations\AmoCRMIntegration();
    $amo->nextOAuth();

});

Route::any('/crm/amo/flera_hus_bot', function (Request $request) {
    Log::info(print_r($request->all(), true));
});

Route::get('/company-page', function () {
    Inertia::setRootView("app");

    return Inertia::render('CompanyPage');
});

Route::get('/user-page', function () {
    Inertia::setRootView("app");

    return Inertia::render('UserPage');
});

Route::get('/mail-page', function () {
    Inertia::setRootView("app");

    return Inertia::render('MailPage');
});

Route::get('/bot-page', function () {
    Inertia::setRootView("app");

    return Inertia::render('BotPage');
});


Route::get('/script-page', function () {
    Inertia::setRootView("app");

    return Inertia::render('ScriptPage');
});

Route::get('/visit-card-page', function () {
    Inertia::setRootView("app");

    return Inertia::render('BotVisitCardConstructorPage');
});

Route::get('/', function () {
    Inertia::setRootView("client");

    return Inertia::render('LandingPage');
});

Route::get('/images-by-company-id/{companyId}/{fileName}',
    [\App\Http\Controllers\Admin\TelegramController::class, 'getFilesByCompanyId']);

Route::get('/images-by-bot-id/{botId}/{fileName}',
    [\App\Http\Controllers\Admin\TelegramController::class, 'getFilesByBotId']);

Route::get('/images/{companySlug}/{fileName}',
    [\App\Http\Controllers\Admin\TelegramController::class, 'getFiles']);

Route::prefix("bot")->group(function () {
    Route::prefix("bots")
        ->controller(BotController::class)
        ->group(function () {
            Route::post("/", "index");
            Route::post("/bot-update", "updateBot");
            Route::post("/user-status", "changeUserStatus");
            Route::post("/users", "loadBotUsers");
            Route::post("/current-bot-user", "getCurrentBotUser");
            Route::delete("/{botId}", "destroy");
            Route::get("/restore/{botId}", "restore");
        });

    Route::prefix("dialog-groups")
        ->controller(\App\Http\Controllers\Admin\BotDialogGroupController::class)
        ->group(function () {
            Route::post("/", "index");
            Route::post("/swap-group", "swapGroup");
            Route::post("/swap-dialog", "swapDialog");
            Route::post("/attach-dialog-to-slug", "attachDialogToSlug");
            Route::post("/unlink-dialog", "unlinkDialog");
            Route::post("/add-group", "addGroup");
            Route::post("/add-dialog", "addDialog");
            Route::post("/duplicate-dialog", "duplicateDialog");
            Route::post("/stop-dialogs", "stopDialogs");
            Route::post("/update-group", "updateGroup");
            Route::post("/update-dialog", "updateDialog");
            Route::delete("/remove-group/{groupId}", "removeGroup");
            Route::delete("/remove-dialog/{dialogId}", "removeDialog");
        });


    Route::prefix("templates")
        ->controller(BotController::class)
        ->group(function () {
            Route::get("/bots", "loadBotsAsTemplate");

            Route::get("/slugs", "loadAllSlugs");
            Route::get("/description", "loadDescriptions");
            Route::post("/telegram-channel-id", "requestTelegramChannel");
            Route::get("/keyboards/{botId}", "loadKeyboards");
            Route::post("/keyboards/{botId}", "loadKeyboardsByText");
            Route::post("/keyboard-template", "createKeyboardTemplate");
            Route::post("/edit-keyboard-template", "editKeyboardTemplate");
            Route::delete("/remove-keyboard-template/{templateId}", "removeKeyboardTemplate");
            Route::get("/slugs/{botId}", "loadSlugs");
            Route::get("/pages/{botId}", "loadPages");

            Route::post("/location", "createLocation");
            Route::get("/location/{companyId}", "loadLocations");
            Route::get("/image-menu/{botId}", "loadImageMenu");
            Route::post("/bot", "createBot");
            Route::post("/bot-lazy", "createBotLazy");
            Route::post("/image-menu", "createImageMenu");
        });

    Route::prefix("companies")
        ->controller(CompanyController::class)
        ->group(function () {
            Route::post("/", "index");
            Route::post("/company", "createCompany");
            Route::post("/company-update", "editCompany");
            Route::delete("/{companyId}", "destroy");
            Route::get("/restore/{companyId}", "restore");
        });

    Route::prefix("slugs")
        ->controller(\App\Http\Controllers\Admin\BotMenuSlugController::class)
        ->group(function () {
            Route::post("/", "index");
            Route::post("/slug", "createSlug");
            Route::post("/slug-update", "updateSlug");
            Route::post("/duplicate/{slugId}", "duplicate");
            Route::get("/reload-params/{slugId}", "reloadParams");
            Route::delete("/{slugId}", "destroy");
        });

    Route::prefix("pages")
        ->controller(BotPageController::class)
        ->group(function () {
            Route::post("/", "index");
            Route::post("/page", "createPage");
            Route::post("/page-update", "updatePage");
            Route::post("/duplicate/{pageId}", "duplicate");
            Route::delete("/{pageId}", "destroy");
        });

    Route::prefix("admins")
        ->controller(AdminBotController::class)
        ->group(function () {
            Route::post('/', "loadActiveAdminList");
            Route::post('/request', "requestCashBack");
            Route::post('/add', "addAdmin");
            Route::post('/remove', "removeAdmin");
            Route::post('/self-remove', "selfRemoveAdmin");
            Route::post('/work-status', "workStatus");
        });

    Route::prefix("cashback")
        ->group(function () {
            Route::post('/history/{botDomain}', [\App\Http\Controllers\Admin\CashBackHistoryController::class, "index"]);
            Route::post('/add', [\App\Http\Controllers\Bots\AdminBotController::class, "addCashBack"]);
            Route::post('/remove', [\App\Http\Controllers\Bots\AdminBotController::class, "removeCashBack"]);
            Route::post('/vip', [\App\Http\Controllers\Bots\AdminBotController::class, "vipStore"]);
            Route::post('/deliveryman', [\App\Http\Controllers\Bots\AdminBotController::class, "deliverymanStore"]);
            Route::post('/user-in-location', [\App\Http\Controllers\Bots\AdminBotController::class, "acceptUserInLocation"]);
        });

    Route::any('/register-webhooks', [\App\Http\Controllers\Admin\TelegramController::class, "registerWebhooks"]);
    Route::any('/{domain}', [\App\Http\Controllers\Admin\TelegramController::class, "handler"]);
});

Route::prefix("web")
    ->group(function () {
        Route::get('/{domain}', [\App\Http\Controllers\Admin\TelegramController::class, "webInterface"]);
        Route::post('/{domain}', [\App\Http\Controllers\Admin\TelegramController::class, "webHandler"]);
    });


Route::get('/restaurant/book-a-table/{botDomain}', function ($botDomain) {

    Inertia::setRootView("bot");

    $bot = \App\Models\Bot::query()
        ->where("bot_domain", $botDomain)
        ->first();

    return Inertia::render('BookingTable', [
        'bot' => $bot,
    ]);
});


Route::get("/restaurant/active-admins/{botDomain}", function ($botDomain) {

    Inertia::setRootView("bot");

    $bot = \App\Models\Bot::query()
        ->where("bot_domain", $botDomain)
        ->first();

    return Inertia::render('AdminList', [
        'bot' => $bot,
    ]);
});


/*Route::post("/admin/cashback-add", function () {
    return "ok";
});*/

Route::post('/get-bot-user', [AdminBotController::class, 'getBotUser']);

Route::get('/admin/{botDomain}/{userId?}', [AdminBotController::class, 'adminMenu']);
Route::get('/admin/work-day/{botDomain}/{userId}', [AdminBotController::class, "workDay"]);
Route::get('/statistic/{botDomain}/{userId}', [AdminBotController::class, "statistic"]);
Route::get('/promotion/{botDomain}/{userId}', [AdminBotController::class, "promotion"]);
Route::get('/restaurant/vip-form/{botDomain}', [AdminBotController::class, "vipForm"]);
Route::get('/deliveryman/vip-form/{botDomain}', [AdminBotController::class, "vipFormDeliveryman"]);


Route::prefix("global-scripts")
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
                Route::post("/vk-auth-link", [\App\Http\Controllers\Globals\VKProductController::class, "getVKAuthLink"]);
                Route::get("/vk-callback", [\App\Http\Controllers\Globals\VKProductController::class, "callback"]);

                Route::post("/products", [\App\Http\Controllers\Admin\ProductController::class, "index"]);
                Route::post("/products-by-ids", [\App\Http\Controllers\Admin\ProductController::class, "getProductsByIds"]);
                Route::get("/products/{productId}", [\App\Http\Controllers\Admin\ProductController::class, "getProduct"]);
                Route::post("/random-products", [\App\Http\Controllers\Admin\ProductController::class, "randomProducts"]);

                Route::prefix("admin")
                    ->group(function () {
                        Route::get('/{botDomain}', [ShopScriptController::class, "shopAdminPage"]);
                    });
            });

        Route::get("{scriptId}/interface/{botDomain}/{path?}", [ShopScriptController::class, "shopHomePage"])
            ->where("scriptId","[0-9]+|route");


    });


Route::get('/welcome', function () {
    Inertia::setRootView("app");
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    Inertia::setRootView("app");
    return Inertia::render('MainPage');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('logout', function () {
    Auth::logout();
    return redirect()->back();
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
