<?php

use App\Events\CashBackEvent;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Admin\TelegramController;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\Company;
use App\Models\ReferralHistory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

Route::get("/test-statistic", function () {
    $bot = Bot::query()->find(21);//$request->bot;
    $botUser = BotUser::query()->find(182);//$request->botUser;

    $userIds = ReferralHistory::query()
        ->where("user_sender_id", $botUser->user_id)
        ->where("bot_id", $bot->id)
        ->get()
        ->pluck("user_recipient_id");


    $botUsersLevel1 = BotUser::query()
        ->whereIn("user_id", $userIds)
        ->where("bot_id", $bot->id)
        ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
        ->get();


    $userIdsLevel2 = ReferralHistory::query()
        ->whereIn("user_sender_id", $botUsersLevel1->pluck("user_id"))
        ->where("bot_id", $bot->id)
        ->get()
        ->pluck("user_recipient_id");

    $botUsersLevel2 = BotUser::query()
        ->whereIn("user_id", $userIdsLevel2)
        ->where("bot_id", $bot->id)
        ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
        ->get();


    $userIdsLevel3 = ReferralHistory::query()
        ->whereIn("user_sender_id", $botUsersLevel2->pluck("user_id"))
        ->where("bot_id", $bot->id)
        ->get()
        ->pluck("user_recipient_id");

    $botUsersLevel3 = BotUser::query()
        ->whereIn("user_id", $userIdsLevel3)
        ->where("bot_id", $bot->id)
        ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
        ->get();

    $tmp = $botUsersLevel1->toArray();

    $level1Index = 0;
    foreach ($tmp as $level1) {

        if (!isset($tmp[$level1Index]["child"]))
            $tmp[$level1Index]["child"] = [];

        $level2Index = 0;
        $tmpLevel2 = $botUsersLevel2->toArray() ?? [];

        $test1 = [];
        foreach ($tmpLevel2 as $level2) {


            if (!isset($tmpLevel2[$level2Index]["child"]))
                $tmpLevel2[$level2Index]["child"] = [];


            $tmpLevel3 = $botUsersLevel3->toArray() ?? [];
            $level3Index = 0;
            $test2 = [];

            foreach ($tmpLevel3 as $level3) {
                if ($tmpLevel2[$level2Index]["id"] === $tmpLevel3[$level3Index]["parent_id"])
                {
                    $test2[] = $tmpLevel3[$level3Index];
                }


                $level3Index++;
            }

            $tmpLevel2[$level2Index]["child"] = $test2;

            if ($tmp[$level1Index]["id"] == $tmpLevel2[$level2Index]["parent_id"]) {
                $test1[] = $tmpLevel2[$level2Index];
            }


            $level2Index++;
        }

        $tmp[$level1Index]["child"] = $test1;

        $level1Index++;

    }
    return $tmp;


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
Route::get("/write-test", function () {
    Log::info("data comming");

    return "success";
});

Route::get("/test-cash", function () {

});
Route::get('/db-transfer', function (Request $request) {
   /*   $users2 = DB::connection('mysql2')->table("users")
          ->get();

      ini_set('max_execution_time', '300000');
      foreach ($users2 as $user2) {

          $user1 = BotUser::query()
              ->where("telegram_chat_id", $user2->telegram_chat_id)
              ->where("bot_id", 46)
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
              'bot_id' => 46,
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
              'bot_id' => 46,
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
