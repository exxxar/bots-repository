<?php

use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Admin\TelegramController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\Company;
use App\Models\ReferralHistory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
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
Route::get("/yclients",function (){

        $login = 79161506189;
        $password =  916150;
        $tokenPartner =  "2w3y7mtfs7n63m79w42c";//"b4e0f3da4ef997e998f7956998e0a61d";

        $response = Http::withHeaders([
            'Authorization' => "Bearer $tokenPartner",
            'Content-Type' => 'application/json',
            'Accept' => "application/vnd.yclients.v2+json"
        ])->asJson()->post('https://api.yclients.com/api/v1/auth', [
            "login"=>"$login",
            "password"=>"$password",
        ]);

        $userToken = $response->object()->data->user_token ?? null;

/*
    $response = Http::withHeaders([
        'Authorization' => "Bearer $tokenPartner, User $userToken",
        'Content-Type' => 'application/json',
        'Accept' => "application/vnd.yclients.v2+json"
    ])->asJson()->delete('https://api.yclients.com/api/v1/company/963541');*/

    dd($response->object());
/*
    $response = Http::withHeaders([
        'Authorization' => "Bearer $tokenPartner, User $userToken",
        'Content-Type' => 'application/json',
        'Accept' => "application/vnd.yclients.v2+json"
    ])->asJson()->post('https://api.yclients.com/api/v1/companies', [
        "title"=>"CashMan",
        "country_id"=>"1",
        "city_id"=>"1",
        "address"=>"1",
        "site"=>"1",
        "coordinate_lat"=>"1",
        "coordinate_lot"=>"1",
        "short_descr"=>"1",
    ]);*/

/*    $response = Http::withHeaders([
        'Authorization' => "Bearer $tokenPartner, User $userToken",
        'Content-Type' => 'application/json',
        'Accept' => "application/vnd.yclients.v2+json"
    ])->asJson()->post('https://api.yclients.com/api/v1/clients/963540', [
        "name"=>"CashMan",
        "surname"=>"1",
        "patronymic"=>"1",
        "phone"=>"+79490000000",
        "email"=>"1",
        "sex_id"=>"1",
        "importance_id"=>"1",
        "discount"=>"1",
        "card"=>"1",
        "birth_date"=>"1",
        "comment"=>"1",
        "spent"=>"1",
        "balance"=>"1",
        "sms_check"=>"1",
        "sms_not"=>"1",
        "categories"=>"1",
        "custom_fields"=>"1",
    ]);*/
    //963540 - при первом запуске сохранить id компании
    dd($response->object());

});

Route::get("/test-export", function (){

    $statuses = \App\Models\ActionStatus::query()->where("bot_id",2)->get();
    return Excel::download(new \App\Exports\ExportArrayData($statuses->toArray()), 'invoices.xlsx',\Maatwebsite\Excel\Excel::XLSX);
});

Route::get("/push-command", function () {

    $botUser = BotUser::query()
        ->where("bot_id", 2)
        ->where("telegram_chat_id", "484698703")
        ->first();
    \App\Facades\BotManager::bot()
        ->setBotUser($botUser)
        ->setApiToken("isushibot")
        ->pushCommand("/diagnostic");
});


Route::get('/db-transfer', function (Request $request) {
/*
    $botId = 41;

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


Route::any('/crm/amo/flera_hus_bot', function (Request $request) {
    Log::info("callback" . print_r($request->all(), true));
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

Route::get("/auth/telegram/{domain}/callback", [AuthenticatedSessionController::class,"telegramAuth"]);

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

require __DIR__ . '/clients/admin-client.php';
require __DIR__ . '/clients/bot-client.php';
require __DIR__ . '/clients/landing.php';
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

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $user->tokens()->delete();

    return ['token' => $user->createToken($request->device_name, ['server:update'])->plainTextToken];
});
