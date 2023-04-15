<?php

use App\Http\Controllers\AdminBotController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Models\BotUser;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use  \App\Http\Controllers\RestaurantBotController;

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

Route::get('/images/{companySlug}/{fileName}',
    [\App\Http\Controllers\TelegramController::class, 'getFiles']);

Route::prefix("bot")->group(function () {
    Route::prefix("templates")
        ->controller(BotController::class)
        ->group(function () {
            Route::get("/bots", "loadBotsAsTemplate");
            Route::get("/keyboards/{botId}", "loadKeyboards");
            Route::get("/slugs/{botId}", "loadSlugs");
            Route::post("/company", "createCompany");
            Route::post("/location", "createLocation");
            Route::post("/bot", "createBot");
            Route::post("/image-menu", "createImageMenu");
        });

    Route::prefix("companies")
        ->controller(CompanyController::class)
        ->group(function () {
            Route::post("/", "index");
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
            Route::post('/history', [\App\Http\Controllers\CashBackHistoryController::class, "index"]);
            Route::post('/add', [\App\Http\Controllers\AdminBotController::class, "addCashBack"]);
            Route::post('/remove', [\App\Http\Controllers\AdminBotController::class, "removeCashBack"]);
            Route::post('/vip', [\App\Http\Controllers\AdminBotController::class, "vipStore"]);
            Route::post('/user-in-location', [\App\Http\Controllers\AdminBotController::class, "acceptUserInLocation"]);
        });

    Route::any('/register-webhooks', [\App\Http\Controllers\TelegramController::class, "registerWebhooks"]);
    Route::any('/{domain}', [\App\Http\Controllers\TelegramController::class, "handler"]);
});


Route::view('/', "landing");


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


Route::get('/callback-form/{botDomain}', function ($botDomain) {

    Inertia::setRootView("bot");

    $bot = \App\Models\Bot::query()
        ->where("bot_domain", $botDomain)
        ->first();
    return Inertia::render('CallBackForm', [
        'bot' => $bot,
    ]);
});
Route::post("/admin/cashback-add", function () {
    return "ok";
});

Route::get('/admin/{botDomain}/{userId}', [AdminBotController::class, 'adminMenu']);
Route::get('/admin/work-day/{botDomain}/{userId}', [AdminBotController::class, "workDay"]);
Route::get('/statistic/{botDomain}/{userId}', [AdminBotController::class, "statistic"]);
Route::get('/promotion/{botDomain}/{userId}', [AdminBotController::class, "promotion"]);
Route::get('/restaurant/vip-form/{botDomain}/{userId}', [AdminBotController::class, "vipForm"]);


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
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
