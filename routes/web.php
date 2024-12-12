<?php

use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Admin\TelegramController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Jobs\SendMessageJob;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\Company;
use App\Models\ReferralHistory;
use App\Models\Role;
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
use Yclients\YclientsApi;

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

Route::get("/redis", function () {
    SendMessageJob::dispatch(
        botId: 21,
        chatId: "484698703",
        message: "test",
        replyKeyboard:null,
        inlineKeyboard:null,
        messageThreadId:null,
        keyboardSettings:null,
    )
        ->delay(now()
            ->addSeconds(3));

    SendMessageJob::dispatch(
        botId: 21,
        chatId: "484698703",
        message: "test 2",
        replyKeyboard:[
            [
                ["text"=>"Главное меню"]
            ]
        ],
        inlineKeyboard:null,
        messageThreadId:null,
        keyboardSettings:null,
    )
        ->delay(now()
            ->addSeconds(5));
});


Route::view("/page-not-found", "error-node")->name("error-node");


Route::middleware(["check-node"])
    ->group(function () {
        require __DIR__ . '/clients/admin-client.php';
        require __DIR__ . '/clients/landing.php';
        require __DIR__ . '/auth.php';

        Route::get("/test-mail", function () {
            $to_name = 'exxxar';
            $to_email = 'exxxar@gmail.com';
            $data = array('name' => "Sam Jose", "body" => "Test mail");
            Mail::send('emails.emails', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Artisans Web Testing Mail');
                $message->from('inbox@your-cashman.com', 'Artisans Web');
            });
        });

        Route::get("/test-word", function () {

            $path = storage_path() . "/app/public";
            if (!file_exists($path . "/document.docx")) {
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($path . "/demo.docx");
                $templateProcessor->setValue('name', 'Akbarali');
                $templateProcessor->setValue('time', '13.02.2021');
                $templateProcessor->setValue('month', 'January');
                $templateProcessor->setValue('state', 'Uzbekistan');
                $templateProcessor->saveAs($path . "/document.docx");
            }
        });

        Route::get("/test-export", function () {
            $statuses = \App\Models\ActionStatus::query()->where("bot_id", 2)->get();
            return Excel::download(new \App\Exports\ExportArrayData($statuses->toArray()), 'invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        });


        Route::get('/db-transfer', function (Request $request) {

            /*$botId = 149;

            $users2 = DB::connection('mysql2')->table("users")
                  ->get();

              ini_set('max_execution_time', '300000');
              foreach ($users2 as $user2) {

                  $user1 = BotUser::query()
                      ->where("telegram_chat_id", $user2->telegram_chat_id)
                      ->where("bot_id", $botId)
                      ->first();

                  if (!is_null($user1))
                      continue;

                  $role = Role::query()
                      ->where("slug", "user")
                      ->first();


                  $telegram_chat_id = $user2->telegram_chat_id;


                  $user = User::query()->updateOrCreate([
                      'email' => "$telegram_chat_id@your-cashman.ru",
                  ],
                      [
                          'name' => $user2->fio_from_telegram ?? $user2->name ?? 'unknown',
                          'password' => bcrypt($telegram_chat_id),
                          'role_id' => $role->id,
                      ]);

                  BotUser::query()->create([
                      'bot_id' => $botId,
                      'user_id' => $user->id ?? null,
                      'username' => $user2->name,
                      'is_vip' => $user2->is_vip ?? false,
                      'is_admin' => $user2->is_admin ?? false,
                      'is_work' => $user2->is_working ?? false,
                      'name' => $user2->fio_from_telegram ?? null,
                      'phone' => $user2->phone ?? null,
                      'birthday' => \Carbon\Carbon::parse($user2->birthday ?? \Carbon\Carbon::now())->format('Y-m-d'),
                      'age' => $user2->age ?? 18,
                      'city' => $user2->city ?? null,
                      'sex' => !is_null($user2->sex) ? ($user2->sex == "Мужской" ? 1 : 0) : 1,
                      'user_in_location' => false,
                      'telegram_chat_id' => $telegram_chat_id,
                      'fio_from_telegram' => $user2->fio_from_telegram ?? null,
                  ]);


                  CashBack::query()->create([
                      'user_id' => $user->id,
                      'bot_id' => $botId,
                      'amount' => $user2->cashback_money ?? 0,
                  ]);


              }
              ini_set('max_execution_time', '300');*/

        });

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
