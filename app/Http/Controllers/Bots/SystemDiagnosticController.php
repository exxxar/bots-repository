<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\BotDialogCommand;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\BotUser;
use Telegram\Bot\FileUpload\InputFile;

class SystemDiagnosticController extends Controller
{
    //

    public function getMyId(...$data)
    {
        BotManager::bot()
            ->reply("Ваш чат id: " . ($data[0]->chat->id ?? 'не указан'));
    }

    public function democircle(...$data)
    {
        BotManager::bot()
            ->replyVideoNote(
                InputFile::create(public_path() . "/videos/vid1.mp4"), [
                [
                    ["text" => "Главное меню"]
                ]
            ],
                "reply"
            );
    }

    public function getDiagnosticTable(...$data)
    {

        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        BotManager::bot()
            ->reply("Диагностическая страница бота")
            ->reply("Ваш чат id: " . ($data[0]->chat->id ?? 'не указан'));

        $bot = BotManager::bot()->getSelf();
        $companyDomain = $bot->company->slug;

        $usersInBot = BotUser::query()
            ->where("bot_id", $bot->id)
            ->count();

        $text = "Бот: " . $bot->bot_domain . " состояние бота - " . ($bot->is_active ? 'включен' : 'выключен') . "\n" .
            "Компания-владелец: " . $companyDomain . "\n" .
            "Пользователей в боте: " . $usersInBot . "\n" .
            "Наличие тоукена: " . (is_null($bot->bot_token) ? "Без тоукена":"С тоукеном") . "\n" .
            "Баланс: " . ($bot->balance ?? 0) . " руб.\n" .
            "Тариф: " . ($bot->tax_per_day ?? 0) . " руб\день\n" .
            "CashBack уровень 1: " . ($bot->level_1 ?? 0) . " %\n" .
            "CashBack уровень 2: " . ($bot->level_2 ?? 0) . " %\n" .
            "CashBack уровень 3: " . ($bot->level_3 ?? 0) . " %\n" .
            "Тариф: " . ($bot->tax_per_day ?? 0) . " руб\день\n" .
            "Тариф: " . ($bot->tax_per_day ?? 0) . " руб\день\n" .
            "Основной канал: " . ($bot->main_channel ?? 'Не подключен') . "\n" .
            "Канал заказов: " . ($bot->order_channel ?? 'Не подключен') . "\n" .
            "Магазин: " . ($bot->vk_shop_link ?? 'Не подключен') . "\n" .
            "Платежная система: " . (is_null($bot->payment_provider_token) ? 'Не подключена' : 'Подключена') . "\n" .
            "Автоматическое начисление кэшбэка при оплате: " . ($bot->auto_cashback_on_payments ? 'Да' : 'Нет') . "\n" .
            "Описание бота:\n <em>" . ($bot->description ?? 'Не задано') . "</em>\n\n" .
            "Привественное сообщение:\n <em>" . ($bot->welcome_message ?? 'Не задано') . "</em>\n\n" .
            "Сообщение тех. работ:\n <em>" . ($bot->maintenance_message ?? 'Не задано') . "</em>\n\n" .
            "Сообщение при блокировке:\n <em>" . ($bot->blocked_message ?? 'Не задано') . "</em>\n";


        $path = storage_path("app/public") . "/companies/$companyDomain/" . ($bot->image ?? 'noimage.jpg');

        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/cashman.jpg"
        );

        BotManager::bot()
            ->replyPhoto("", $file)
            ->reply($text);

        $pages = BotPage::query()
            ->where("bot_id", $bot->id)
            ->get();

        if (count($pages)) {
            $tmp = "";
            $keyboard = [];
            $rowTmpKeyboard = [];
            $index = 1;
            foreach ($pages as $page) {
                $tmp .= "$index# <b>" . ($page->slug->command ?? 'Не указано')."</b>\n";

                if ($index % 4 != 0) {
                    $rowTmpKeyboard[] = [
                        "text" => $index,
                        "callback_data" => $page->slug->command ?? $page->slug->slug
                    ];
                } else {
                    $rowTmpKeyboard[] = [
                        "text" => $index,
                        "callback_data" => $page->slug->command ?? $page->slug->slug
                    ];

                    $keyboard[] = $rowTmpKeyboard;
                    $rowTmpKeyboard = [];
                }

                $index++;
            }

            if (count($rowTmpKeyboard)>0){
                $keyboard[] = $rowTmpKeyboard;
            }

            BotManager::bot()
                ->replyInlineKeyboard("Доступные страницы <b>(".count($pages)." стр.)</b> в боте:\n$tmp", $keyboard);

        } else
            BotManager::bot()
                ->reply("Страницы в боте отсутствуют");

        $slugs = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("is_global", true)
            ->get();

        if (count($slugs) > 0) {
            $tmp = "";
            $keyboard = [];
            $rowTmpKeyboard = [];
            $index = 1;

            foreach ($slugs as $slug) {
                $tmp .= "$index# <b>" . ($slug->command ?? 'Не указано')."</b>\n";

                if ($index % 4 != 0) {
                    $rowTmpKeyboard[] = [
                        "text" => $index,
                        "callback_data" => $slug->command ?? $slug->slug
                    ];
                } else {
                    $rowTmpKeyboard[] = [
                        "text" => $index,
                        "callback_data" => $slug->command ?? $slug->slug
                    ];

                    $keyboard[] = $rowTmpKeyboard;
                    $rowTmpKeyboard = [];
                }

                $index++;
            }

            if (count($rowTmpKeyboard)>0){
                $keyboard[] = $rowTmpKeyboard;
            }

            BotManager::bot()
                ->replyInlineKeyboard("Подключенные скрипты <b>(".count($slugs)." ед.)</b> в боте:\n$tmp", $keyboard);

        } else
            BotManager::bot()
                ->reply("Подключенные скрипты в боте отсутствуют");

        $dialogs = BotDialogCommand::query()
            ->where("bot_id", $bot->id)
            ->get();

        if (count($dialogs)>0){
            $tmp = "";
            foreach ($dialogs as $dialog)
                $tmp .= ($dialog->pre_text ?? 'Не задан')."\n";

            BotManager::bot()
                ->reply("Подключенные диалоги (".count($dialogs)." ед):\n$tmp");
        }
        else
            BotManager::bot()
                ->reply("Подключенные диалоги в боте отсутствуют");

    }
}
