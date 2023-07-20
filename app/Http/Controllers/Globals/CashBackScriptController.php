<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\CashBack;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class CashBackScriptController extends Controller
{
    const SCRIPT = "global_cashback_main";


    public function specialCashBackSystem(...$config)
    {
        $bot = BotManager::bot()->getSelf();


        $botDomain = $bot->bot_domain;

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_vip) {
            $bot = BotManager::bot()->getSelf();

            \App\Facades\BotManager::bot()
                ->replyPhoto("Заполни эту анкету и получит достук к системе CashBack",
                    InputFile::create(public_path() . "/images/cashman2.jpg"),
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                                "url" => env("APP_URL") . "/restaurant/vip-form/$botDomain"
                            ]],
                        ],

                    ]);

            return;
        }

        $botUser = BotManager::bot()->currentBotUser();

        $data = "001" . $botUser->telegram_chat_id;
        Log::info($data);
        $qr = "https://t.me/$botDomain?start=" .
            base64_encode($data);



        $cashBack = CashBack::query()
            ->where("bot_id", $bot->id)
            ->where("user_id", $botUser->user_id)
            ->first();

        $amount = is_null($cashBack) ? 0 : ($cashBack->amount ?? 0);

        $companyTitle = $bot->company->title ?? 'CashMan';

        \App\Facades\BotManager::bot()
            ->replyPhoto("Тест: У вас <b>$amount</b> руб.!\n
Для начисления CashBack при оплате за услуги дайте отсканировать данный QR-код сотруднику <b>$companyTitle</b>",
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));

        \App\Facades\BotManager::bot()
            ->sendReplyMenu("Меню управления CashBack-ом", "menu_level_2_restaurant_1");
    }
}
