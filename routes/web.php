<?php

use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Classes\Tinkoff;
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

Route::get("/table", function (Request $request) {

    $bot = Bot::query()
        ->where("bot_domain", "nextitgroup_bot")
        ->first();

    $botUser =BotUser::query()
        ->where("bot_id",$bot->id)
        ->where("telegram_chat_id","484698703")
        ->first();

    $slugId = 2606;
    $tableNumber = 3;


    $path = env("APP_URL") . "/bot-client/simple/%s?slug=%s&hide_menu#/s/table-menu";


    $table = Table::query()
        ->where("bot_id", $bot->id)
        ->where("number", $tableNumber)
        ->whereNull("closed_at")
        ->first();

    if (is_null($table)) {
        $table = Table::query()
            ->create([
                'bot_id' => $bot->id,
                'creator_id' => $botUser->id,
                'officiant_id' => null,
                'number' => $tableNumber,
                'closed_at' => null,
                'additional_services' => null,
                'config' => null,
            ]);

        $table->clients()->sync($botUser->id);

    } else {

        $tableWithClient = Table::query()
            ->where("bot_id", $bot->id)
            ->where("number", $tableNumber)
            ->whereNull("closed_at")
            ->whereHas('clients', function ($query) use ($botUser) {
                $query->where('id', $botUser->id);
            })->first();

        if (is_null($tableWithClient)) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendInlineKeyboard(
                    $botUser->telegram_chat_id,
                    "Вы хотите присоединиться за столик №$tableNumber. За этим столиком уже сидят, запросить разрешение?",
                    [
                        [
                            ["text" => "🛎️Запросить", "callback_data" => "/request_table_join $tableNumber $slugId"],
                        ]
                    ]
                );
            return;
        }

    }

    BotMethods::bot()
        ->whereBot($bot)
        ->sendInlineKeyboard(
            $botUser->telegram_chat_id,
            "Добро пожаловать за столик №$tableNumber",
            [
                [
                    ["text" => "🛎️Открыть меню",
                        "web_app" => [
                            "url" => sprintf(
                                $path,
                                $bot->bot_domain,
                                $slugId,
                                $botUser->id,
                            )
                        ]
                    ],
                ]
            ]
        );

    dd( sprintf(
        $path,
        $bot->bot_domain,
        $slugId,
        $botUser->id,
    ));

});

Route::get("/test2", function () {
    $api_url = 'https://securepay.tinkoff.ru/v2/';
    $terminal = '1708340156876';
    $secret_key = '679vo0qxmz7j5wob';

    $tinkoff = new Tinkoff($api_url, $terminal, $secret_key);

    $payment = [
        'OrderId' => '123457',        //Ваш идентификатор платежа
        'Amount' => '1244',           //сумма всего платежа в рублях
        'Language' => 'ru',            //язык - используется для локализации страницы оплаты
        'Description' => 'Some buying',   //описание платежа
        'Email' => 'user@email.com',//email покупателя
        'Phone' => '89099998877',   //телефон покупателя
        'Name' => 'Customer name', //Имя покупателя
        'Taxation' => 'usn_income'     //Налогооблажение
    ];


//подготовка массива с покупками
    $items[] = [
        'Name' => 'Название товара 1234',
        'Quantity' => 1,
        'Price' => '1244',    //цена товара в рублях
        'NDS' => 'vat20',  //НДС //tax
    ];

//Получение url для оплаты
    $paymentURL = $tinkoff->paymentURL($payment, $items);

    //dd($paymentURL);
//Контроль ошибок
    if (!$paymentURL) {
        echo($tinkoff->error);
    } else {
        $payment_id = $tinkoff->payment_id;
        dd($payment_id);
    }
});

Route::get('/test-token', function () {
    function generateToken($requestData, $password)
    {
        // Извлекаем только параметры корневого объекта
        $tokenData = [];
        foreach ($requestData as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $tokenData[$key] = $value;
            }
        }

        // Добавляем параметр Password
        $tokenData['Password'] = $password;

        // Сортируем массив по ключу в алфавитном порядке
        ksort($tokenData);

        // Конкатенируем значения в одну строку
        $concatenatedString = implode('', $tokenData);

        // Применяем хеш-функцию SHA-256
        $token = hash('sha256', $concatenatedString);

        return $token;
    }

    function sendPaymentRequest($formData)
    {
        $url = 'https://securepay.tinkoff.ru/v2/InitPayments';

        $jsonData = json_encode($formData);

        // Инициализация cURL
        $ch = curl_init($url);

        // Настройка параметров cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        // Выполнение запроса
        $response = curl_exec($ch);

        // Обработка ошибок
        if (curl_errno($ch)) {
            throw new Exception('Ошибка cURL: ' . curl_error($ch));
        }

        // Получение HTTP-кода ответа
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // Проверка успешности запроса
        if ($httpCode !== 200) {
            throw new Exception("Ошибка сервера: HTTP код $httpCode");
        }

        return $response;
    }

// Получаем данные из формы
    $requestData = [
        "TerminalKey" => "1708340156876DEMO",
        "Amount" => 19200,
        "OrderId" => "21090",
        "Description" => "Подарочная карта на 1000 рублей",
        "DATA" => [
            "Phone" => "+71234567890",
            "Email" => "a@test.com"
        ],
        "Receipt" => [
            "Email" => "a@test.ru",
            "Phone" => "+79031234567",
            "Taxation" => "osn",
            "Items" => [
                [
                    "Name" => "Наименование товара 1",
                    "Price" => 10000,
                    "Quantity" => 1,
                    "Amount" => 10000,
                    "Tax" => "vat10",
                    "Ean13" => "303130323930303030630333435"
                ],
                [
                    "Name" => "Наименование товара 2",
                    "Price" => 20000,
                    "Quantity" => 2,
                    "Amount" => 40000,
                    "Tax" => "vat20"
                ],
                [
                    "Name" => "Наименование товара 3",
                    "Price" => 30000,
                    "Quantity" => 3,
                    "Amount" => 90000,
                    "Tax" => "vat10"
                ]
            ]
        ]
    ];

    $password = "vmjexnzunuvm0507";
    $token = generateToken($requestData, $password);

// Добавляем токен в данные запроса
    $requestData['Token'] = $token;


// Вывод результата
    echo "Сгенерированный токен: " . $token . PHP_EOL;
    echo "Обновленные данные запроса:" . PHP_EOL;

    try {
        // Отправка запроса
        $response = sendPaymentRequest($requestData);

        // Обработка ответа
        echo "Ответ сервера: " . $response;
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage();
    }
});

Route::get("/crc", function () {

    function calculateCRC16($input)
    {
        $polynomial = 0x1021; // Полином CRC-16-CCITT
        $crc = 0xFFFF;       // Начальное значение

        // Преобразование строки в массив байтов
        $bytes = unpack('C*', $input);

        foreach ($bytes as $byte) {
            $crc ^= ($byte << 8); // XOR старших 8 бит CRC с текущим байтом

            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) { // Проверка старшего бита
                    $crc = ($crc << 1) ^ $polynomial;
                } else {
                    $crc = $crc << 1;
                }
            }

            $crc &= 0xFFFF; // Ограничиваем 16 битами
        }

        return strtoupper(dechex($crc));
    }

// Пример строки
    $url = "https://qr.nspk.ru/BS1A003M3CFQC2HF97NBLS9CAPH3ABCF?type=02&bank=000000000001&sum=10605&cur=RUB";

// Убедитесь, что передаете строку точно до параметра crc
    $crc = calculateCRC16($url);

    echo "CRC-16: $url&crc=$crc\n";
    /* SendMessageJob::dispatch(
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
             ->addSeconds(5));*/
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
