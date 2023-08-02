<?php

use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Admin\BotPageController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TelegramController;
use App\Http\Controllers\Bots\AdminBotController;
use App\Http\Controllers\Globals\InstagramQuestScriptController;
use App\Http\Controllers\Globals\ShopScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneScriptController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

Route::get('/test-files', function (){
    $files = Storage::disk('public')->allFiles("/companies");

    return $files;
});


Route::get("/test-amo", function () {
    $amo = new \App\Integrations\AmoCRMIntegration((object)[
        "clientId" => null,
        "clientSecret" => null,
        "authCode" => null,
        "domain" => null,
        "subdomain" => null,
    ]);
    //$amo->firstOAuth();
    $amo->nextOAuth();

});

Route::any('/crm/amo/flera_hus_bot', function (Request $request) {
    Log::info("callback".print_r($request->all(), true));
});


Route::get('/', function () {
    Inertia::setRootView("landing");
    return Inertia::render('LandingPage');
});

Route::post('/remove-file',
    [TelegramController::class, 'removeFile']);

Route::get('/companies/{company}/{fileName}',
    [TelegramController::class, 'getStorageFile']);

Route::get('/images-by-company-id/{companyId}/{fileName}',
    [TelegramController::class, 'getFilesByCompanyId']);

Route::get('/images-by-bot-id/{botId}/{fileName}',
    [TelegramController::class, 'getFilesByBotId']);

Route::get('/images/{companySlug}/{fileName}',
    [TelegramController::class, 'getFiles']);


Route::prefix("web")
    ->group(function () {
        Route::get('/{domain}', [TelegramController::class, "webInterface"]);
        Route::post('/{domain}', [TelegramController::class, "webHandler"]);
    });

require __DIR__ . '/clients/admin-client.php';
require __DIR__ . '/clients/bot-client.php';
require __DIR__ . '/auth.php';
