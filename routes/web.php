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

use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

Route::get("/bottest", [\App\Http\Controllers\Globals\FastoranController::class,"shopList"]);

Route::get("/test", function (){
    $name ='Шипилов Егор Олегович';
    $course ='Вы выиграли сертификат на бургер!';
    $date =  date('d.m.Y');

    $templatePath = storage_path('app/public/certificates/certificate_template.png');

    // Загружаем изображение
    $image = imagecreatefrompng($templatePath);

    // Получаем размеры изображения
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    // Устанавливаем цвет текста (черный)
    $textColor = imagecolorallocate($image, 0, 0, 0);

    // Указываем путь к шрифту (TrueType)
    $fontPath = storage_path('app/public/certificates/Ura Bum Bum SP.ttf'); // Добавь свой шрифт в public/fonts

    // Размеры шрифта
    $fontSizeName = 30;
    $fontSizeInfo = 20;

    // Высоты строк
    $yName = $imageHeight / 2 - 30;
    $yCourse = $yName + 40;
    $yDate = $yCourse + 30;

    // Функция для отцентровки текста
    $centerText = function($text, $fontSize, $y) use ($image, $imageWidth, $textColor, $fontPath) {
        $box = imagettfbbox($fontSize, 0, $fontPath, $text);
        $textWidth = abs($box[2] - $box[0]);
        $x = ($imageWidth - $textWidth) / 2;
        imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
    };

    $centerText($name, $fontSizeName, $yName);
    $centerText("Ваш приз: $course", $fontSizeInfo, $yCourse);
    $centerText("Дата выигрыша: $date", $fontSizeInfo, $yDate);

    $qrText = "https://t.me/exxxar";
    // Генерируем QR в PNG и получаем как строку
    $qrPng = QrCode::format('png')->size(100)->margin(1)->generate($qrText);

    // Создаём изображение QR-кода из строки
    $qrImage = imagecreatefromstring($qrPng);

    // Координаты для размещения (правый нижний угол с отступами)
    $qrWidth = imagesx($qrImage);
    $qrHeight = imagesy($qrImage);
    $padding = 30;

    $qrX = ($imageWidth/2) - $qrWidth + 50;
    $qrY = ($imageHeight/2) - $qrHeight + 200;

    // Накладываем QR-код
    imagecopy($image, $qrImage, $qrX, $qrY, 0, 0, $qrWidth, $qrHeight);
    // Буферизуем вывод
    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();

    // Освобождаем память
    imagedestroy($image);

    $bot = Bot::query()
        ->where("bot_domain",'nextitgroup_bot')
        ->first();

    BotMethods::bot()
        ->whereBot($bot)
        ->sendPhoto(
            484698703,
            "Информация о сертификате",
            InputFile::createFromContents($imageData, "certificate.png")
        );
   /* // Возвращаем как ответ с правильными заголовками
    return response($imageData)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="certificate.png"');*/
});
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
