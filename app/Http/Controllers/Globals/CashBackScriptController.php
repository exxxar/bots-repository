<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\CashBack;
use Illuminate\Support\Collection;
use Telegram\Bot\FileUpload\InputFile;

class CashBackScriptController extends Controller
{
    const SCRIPT = "global_cashback_main";


    public function specialCashBackSystem(...$config)
    {
        $bot = BotManager::bot()->getSelf();

        $botDomain = $bot->bot_domain;


        $qr = "https://t.me/$botDomain?start=" .
            base64_encode("001" . BotManager::bot()->getCurrentChatId());

        $botUser = BotManager::bot()->currentBotUser();

        $cashBack = CashBack::query()
            ->where("bot_id", $bot->id)
            ->where("user_id", $botUser->user_id)
            ->first();

        $amount = is_null($cashBack) ? 0 : ($cashBack->amount ?? 0);

        $companyTitle = $bot->company->title ?? 'CashMan';

        \App\Facades\BotManager::bot()
            ->replyPhoto("У вас <b>$amount</b> руб.!\n
Для начисления CashBack при оплате за услуги дайте отсканировать данный QR-код сотруднику <b>$companyTitle</b>",
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));

        \App\Facades\BotManager::bot()
            ->sendReplyMenu("Меню управления CashBack-ом", "menu_level_2_restaurant_1");
    }
}
