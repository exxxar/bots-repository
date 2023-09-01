<?php

use App\Facades\BusinessLogic;
use App\Http\Controllers\Admin\TelegramController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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

Route::get('/test-logic', function (Request $request){

    $bot = $request->bot;
    $botUser = $request->botUser;

        return BusinessLogic::administrative()
            ->setBot($bot)
            ->setBotUser($botUser)
            ->test();


});

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

Route::prefix("bot")
    ->group(function(){
        Route::any('/register-webhooks', [\App\Http\Controllers\Admin\TelegramController::class, "registerWebhooks"]);
        Route::any('/{domain}', [\App\Http\Controllers\Admin\TelegramController::class, "handler"]);
    });

Route::prefix("web")
    ->group(function () {
        Route::get('/{domain}', [TelegramController::class, "webInterface"]);
        Route::post('/{domain}', [TelegramController::class, "webHandler"]);
    });

require __DIR__ . '/clients/admin-client.php';
require __DIR__ . '/clients/bot-client.php';
require __DIR__ . '/auth.php';

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::query()
        ->where('email', $request->email)
        ->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $user->tokens()->delete();

    return ['token' => $user->createToken($request->device_name,['server:update'])->plainTextToken];
});
