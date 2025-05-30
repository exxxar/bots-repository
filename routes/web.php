<?php

use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Classes\Tinkoff;
use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Admin\TelegramController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Jobs\SendMessageJob;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\Company;
use App\Models\ReferralHistory;
use App\Models\Role;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use danog\Decoder\FileId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Telegram\Bot\FileUpload\InputFile;


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

/*Route::get("/test", function (){
   $bitrix = new \App\Http\BusinessLogic\BitrixService("");
   return $bitrix->getStatusList();
});*/
Route::any("/payment-service-notify/tinkoff", [BotController::class, "tinkoffInvoiceServiceCallback"]);
Route::any("/payment-products-notify/tinkoff/{domain}", [BotController::class, "tinkoffInvoiceProductsServiceCallback"]);
Route::view("/page-not-found", "error-node")->name("error-node");


Route::middleware(["check-node"])
    ->group(function () {
        require __DIR__ . '/clients/admin-client.php';
        require __DIR__ . '/clients/landing.php';
        require __DIR__ . '/auth.php';

        Route::get('/', function () {
            Inertia::setRootView("landing-new");
            return Inertia::render('LandingPage');
        });

        Route::get('/politics', function () {
            Inertia::setRootView("landing-new");
            return Inertia::render('PoliticsPage');
        });

        Route::get('/terms', function () {
            Inertia::setRootView("landing-new");
            return Inertia::render('TermsPage');
        });

        Route::get('/partner-terms', function () {
            Inertia::setRootView("landing-new");
            return Inertia::render('PartnerTermsPage');
        });

        Route::get('/wiki', function () {
            Inertia::setRootView("landing-new");
            return Inertia::render('WikiPage');
        });

        Route::get('/history', function () {
            Inertia::setRootView("landing");
            return Inertia::render('LandingForProjectPage');
        });

        Route::get("/auth/telegram/{domain}/callback", [AuthenticatedSessionController::class, "telegramAuth"]);
        Route::any("/auth/tg-link", [AuthenticatedSessionController::class, "telegramLinkAuth"]);

    });

Route::any("/front-pad/callback/{domain}", function (Request $request, $domain) {
    Log::info("front-pad callback $domain" . print_r($request->all(), true));
    return "success";
});

Route::any("/integrations/1c/callback", function (Request $request) {
    Log::info("integrations" . print_r($request->all(), true));
    return "success";
});

Route::post('/remove-file',
    [TelegramController::class, 'removeFile']);

Route::get('/companies/{company}/{fileName}',
    [TelegramController::class, 'getStorageFile']);

Route::get('/file-by-file-id/{fileId}',
    [TelegramController::class, 'getFileByMediaContentId']);

Route::get('/file-by-file-id-and-bot-domain/{fileId}/{domain}',
    [TelegramController::class, 'getFileByMediaContentIdAndBotDomain']);


Route::get('/images-by-company-id/{companyId}/{fileName}',
    [TelegramController::class, 'getFilesByCompanyId']);

Route::get('/images-by-bot-id/{botId}/{fileName}',
    [TelegramController::class, 'getFilesByBotId']);

Route::get('/images/{companySlug}/{fileName}',
    [TelegramController::class, 'getFiles']);


Route::prefix("bot")
    ->group(function () {
        Route::any('/register-webhooks', [\App\Http\Controllers\Admin\TelegramController::class, "registerWebhooks"]);
        Route::any('/{domain}', [\App\Http\Controllers\Admin\TelegramController::class, "handler"]);
    });

Route::prefix("web")
    ->group(function () {
        Route::get('/{domain}', [TelegramController::class, "webInterface"]);
        Route::post('/{domain}', [TelegramController::class, "webHandler"]);
    });


require __DIR__ . '/clients/bot-client.php';
require __DIR__ . '/clients/mobile.php';

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::query()
        ->where('email', $request->email)
        ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $user->tokens()->delete();

    return ['token' => $user->createToken($request->device_name, ['server:update'])->plainTextToken];
});
