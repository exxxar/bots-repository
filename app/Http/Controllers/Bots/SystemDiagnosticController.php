<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotDialogCommand;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\ReferralHistory;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        $botId = $data[3] ?? null;

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

        $bot = !is_null($botId) ? Bot::query()->find($botId) : null;

        if (is_null($bot) || is_null($botId))
            $bot = BotManager::bot()->getSelf();

        $this->makeDiagnostic($bot);
    }

    private function makeDiagnostic($bot)
    {
        $companyDomain = $bot->company->slug;

        $usersInBot = BotUser::query()
            ->where("bot_id", $bot->id)
            ->count();

        $text = "Бот: " . $bot->bot_domain . " состояние бота - " . ($bot->is_active ? 'включен' : 'выключен') . "\n" .
            "Компания-владелец: " . $companyDomain . "\n" .
            "Пользователей в боте: " . $usersInBot . "\n" .
            "Наличие тоукена: " . (is_null($bot->bot_token) ? "Без тоукена" : "С тоукеном") . "\n" .
            "Баланс: " . ($bot->balance ?? 0) . " руб.\n" .
            "Тариф: " . ($bot->tax_per_day ?? 0) . " руб\день\n" .
            "CashBack уровень 1: " . ($bot->level_1 ?? 0) . " %\n" .
            "CashBack уровень 2: " . ($bot->level_2 ?? 0) . " %\n" .
            "CashBack уровень 3: " . ($bot->level_3 ?? 0) . " %\n" .
            "Основной канал: " . ($bot->main_channel ?? 'Не подключен') . "\n" .
            "Канал заказов: " . ($bot->order_channel ?? 'Не подключен') . "\n" .
            "Магазин: " . ($bot->vk_shop_link ?? 'Не подключен') . "\n" .
            "Платежная система: " . (is_null($bot->payment_provider_token) ? 'Не подключена' : 'Подключена') . "\n" .
            "AMO CRM: " . (is_null($bot->amo) ? 'Не подключена' : 'Подключена') . "\n" .
            "Автоматическое начисление кэшбэка при оплате: " . ($bot->auto_cashback_on_payments ? 'Да' : 'Нет') . "\n\n" .
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
                $tmp .= "$index# <b>" . ($page->slug->command ?? 'Не указано') . "</b>\n";

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

            if (count($rowTmpKeyboard) > 0) {
                $keyboard[] = $rowTmpKeyboard;
            }

            BotManager::bot()
                ->replyInlineKeyboard("Доступные страницы <b>(" . count($pages) . " стр.)</b> в боте:\n$tmp", $keyboard);

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
                $tmp .= "$index# <b>" . ($slug->command ?? 'Не указано') . "</b>\n";

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

            if (count($rowTmpKeyboard) > 0) {
                $keyboard[] = $rowTmpKeyboard;
            }

            BotManager::bot()
                ->replyInlineKeyboard("Подключенные скрипты <b>(" . count($slugs) . " ед.)</b> в боте:\n$tmp", $keyboard);

        } else
            BotManager::bot()
                ->reply("Подключенные скрипты в боте отсутствуют");

        $dialogs = BotDialogCommand::query()
            ->where("bot_id", $bot->id)
            ->get();

        if (count($dialogs) > 0) {
            $tmp = "";
            foreach ($dialogs as $dialog)
                $tmp .= ($dialog->pre_text ?? 'Не задан') . "\n";

            BotManager::bot()
                ->reply("Подключенные диалоги (" . count($dialogs) . " ед):\n$tmp");
        } else
            BotManager::bot()
                ->reply("Подключенные диалоги в боте отсутствуют");

    }

    public function startWithParam(...$data)
    {
        BotManager::bot()->stopBotDialog();

        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        $message = $bot->welcome_message ?? null;

        //  Log::info("startWithParam data".print_r($data[3], true));

        if (!is_null($data[3])) {
            $pattern_simple = "/([0-9]{3})([0-9]+)/";
            $pattern_extended = "/([0-9]{3})([0-9]{8,10})S([0-9]+)/";

            $string = base64_decode($data[3]);

            preg_match_all(strlen($string) <= 13 ? $pattern_simple : $pattern_extended, $string, $matches);

            $code = $matches[1][0] ?? null;
            $request_id = $matches[2][0] ?? null;
            $slug_id = $matches[3][0] ?? 'route';

            // Log::info("code = $code request_telegram_chat_id " .$request_telegram_chat_id);

            //$qrCode = new QRCodeHandler($code, $request_user_id);

            if ($botUser->is_admin) {
                // Log::info("startWithParam is_admin $code $request_telegram_chat_id $slug_id");
                switch ($code) {
                    default:
                    case "001":
                        $text = "Основная административная панель";
                        $path = env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route&user=$request_id#/admin-main";
                        break;

                    case "002":
                        $text = "Административное меню системы бонусных накоплений";
                        $path = env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slug_id&user=$request_id#/admin-bonus-product";
                        break;

                    case "003":
                        $text = "Обратная связь с пользователем";
                        $path = env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route&user=$request_id#/admin-main";
                        break;


                }


                BotManager::bot()->replyInlineKeyboard(
                    $text,
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB0Перейти в административное меню",
                                "web_app" => [
                                    "url" => $path
                                ]
                            ],
                        ]
                    ]
                );


            }

            if ($code === "004") {
                BotManager::bot()->runPage($request_id);
                return;
            }


            if (BotManager::bot()->currentBotUser()->telegram_chat_id == $request_id) {
                BotManager::bot()
                    ->reply(
                        "Вы перешли по своей собственной ссылке... вы, конечно, себе друг, но CashBack достанется кому-то одному..."
                    );

                return;

            }

            $userBotUser = BotUser::query()
                ->where("telegram_chat_id", $request_id)
                ->where("bot_id", BotManager::bot()->getSelf()->id)
                ->first();


            $ref = ReferralHistory::query()
                ->where("user_sender_id", $userBotUser->user_id)
                ->where("user_recipient_id", $botUser->user_id)
                ->where("bot_id", $botUser->bot_id)
                ->first();

            if (is_null($ref)) {
                ReferralHistory::query()->create([
                    'user_sender_id' => $userBotUser->user_id,
                    'user_recipient_id' => $botUser->user_id,
                    'bot_id' => $botUser->bot_id,
                    'activated' => true,
                ]);

                $userName1 = BotMethods::prepareUserName($botUser);
                $userName2 = BotMethods::prepareUserName($userBotUser);

                $botUser->parent_id = $userBotUser->id;
                $botUser->save();

                BotMethods::bot()
                    ->whereId($botUser->bot_id)
                    ->sendMessage(
                        $userBotUser->telegram_chat_id,
                        "По вашей ссылке перешел пользователь $userName1"
                    )
                    ->sendMessage(
                        $botUser->telegram_chat_id,
                        "Вас и вашего друга $userName2 теперь объеденяет еще и CashBack;)"
                    );
            }


            if (is_null($userBotUser)) {
                BotManager::bot()->reply("Данный код не корректный!");
                return;
            }

            $userBotUser->user_in_location = true;
            $userBotUser->save();

            BotManager::bot()->reply($message);
        }


        BotManager::bot()
            ->replyInlineKeyboard("Отлично! Вы перешли по ссылке друга и теперь готовы к большому CashBack-путешествию:)",
                [
                    [
                        ["text" => "Поехали! ЖМИ:)", "callback_data" => "/start"],
                    ],

                ]);
    }

    public function payForBot(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        if (!$botUser->is_admin) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $values = [500, 1000, 2000, 5000, 10000, 25000, 50000];

        $weekTaxFee = $bot->tax_per_day * 7;
        $monthTaxFee = $bot->tax_per_day * 31;
        $halfYearTaxFee = $bot->tax_per_day * 31 * 6;
        $yearTaxFee = $bot->tax_per_day * 31 * 12;


        $keyboard = [];
        $row = [];

        $rowIndex = 1;
        foreach ($values as $value) {
            $row[] = ["text" => "$value ₽", "callback_data" => "/pay_tax_fee $value"];

            if ($rowIndex % 3 == 0) {
                $keyboard[] = $row;
                $row = [];
            }

            $rowIndex++;

        }

        if (!empty($row))
            $keyboard[] = $row;

        $message = "Ваш баланс: <b>" . ($bot->balance ?? 0) . " ₽</b>\n" .
            "Ваш тариф: <b>" . ($bot->tax_per_day ?? 0) . " ₽/день</b>\n";

        BotManager::bot()
            ->replyInlineKeyboard($message . "Выберите сумму оплаты из вариантов:", $keyboard);

        $keyboard = [
            [
                ["text" => "Неделя $weekTaxFee ₽", "callback_data" => "/pay_tax_fee $weekTaxFee"],
                ["text" => "Месяц $monthTaxFee ₽", "callback_data" => "/pay_tax_fee $monthTaxFee"],

            ],

            [
                ["text" => "Пол года $halfYearTaxFee ₽", "callback_data" => "/pay_tax_fee $halfYearTaxFee"],
                ["text" => "Год $yearTaxFee ₽", "callback_data" => "/pay_tax_fee $yearTaxFee"],
            ],


        ];

        BotManager::bot()->replyInlineKeyboard("или согласно вашему тарифу:", $keyboard);


    }

    public function payTaxFee(...$data)
    {


        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $value = $data[3] ?? null;

        if (is_null($value)) {
            BotManager::bot()->reply("Вы не выбрали нужную для оплаты сумму! Повторите операцию");
            return;
        }

        $prices = [
            [
                "label" => "Оплата услуг сервиса CashMan",
                "amount" => $value * 100
            ]
        ];
        $payload = bin2hex(Str::uuid());

        $providerToken = $bot->payment_provider_token;
        $currency = "RUB";

        Transaction::query()->create([
            'user_id' => $botUser->user_id,
            'bot_id' => $bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $value,
            'status' => 0,
            'products_info' => (object)[
                "payload" => $payloadData ?? null,
                "prices" => $prices,
            ],
        ]);

        $needs = [
            "need_name" => true,
            "need_phone_number" => true,
            "need_email" => true,
            "need_shipping_address" => false,
            "send_phone_number_to_provider" => true,
            "send_email_to_provider" => true,
            "is_flexible" => false,
            "disable_notification" => false,
            "protect_content" => false,
        ];

        $keyboard = [
            [
                ["text" => "Оплатить $value ₽", "pay" => true],
            ],

        ];

        $providerData = (object)[
            "receipt" => [
                (object)[
                    "description" => "Оплата услуг сервиса CashMan",
                    "quantity" => "1.00",
                    "amount" => (object)[
                        "value" => $value,
                        "currency" => $currency
                    ],
                    "vat_code" => 0
                ]
            ]
        ];

        \App\Facades\BotManager::bot()
            ->replyInvoice(
                "CashMan", "Оплата услуг сервиса CashMan", $prices, $payload, $providerToken, $currency, $needs, $keyboard,
                $providerData
            );
    }

    public function cashmanPayment(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        $paymentUrl = env("PAYMENT_BOT_SERVICE_URL") ?? null;

        if (is_null($paymentUrl)) {
            BotManager::bot()->reply("Сервис оплаты временно недоступен!");
            return;
        }

        $tmpBotId = (str_repeat("0", 10 - strlen($bot->id))) . $bot->id;
        $tmpBotUserId = (str_repeat("0", 10 - strlen($botUser->id))) . $botUser->id;

        $bcryptLink = base64_encode("005U" . $tmpBotUserId . "B" . $tmpBotId);
        $url = "$paymentUrl?start=$bcryptLink";

        BotManager::bot()
            ->replyInlineKeyboard("Внимание! Сейчас вас перенаправит в бот оплаты", [
                [
                    ["text" => "💸Перейти к оплате", "url" => "$url"]
                ]
            ]);
    }
}
