<?php

namespace App\Http\Controllers\Bots;

use App\Enums\OrderStatusEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Facades\StartCodesService;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotDialogCommand;
use App\Models\BotMedia;
use App\Models\BotMenuSlug;
use App\Models\BotNote;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\Documents;
use App\Models\Order;
use App\Models\ReferralHistory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class SystemDiagnosticController extends Controller
{
    //

    public function testConfig(...$data)
    {
        BotManager::bot()
            ->testSetMyName("НОВОЕ ИМЯ БОТА");
    }

    public function demodice(...$data)
    {
        BotManager::bot()
            ->replyDice();
    }

    public function uploadFilesToBot(...$data){

        $bot = BotManager::bot()->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        BotManager::bot()
           ->sendInlineKeyboard($botUser->telegram_chat_id ?? null,
            "🗃️Менеджер файлов",
            [
                [
                    [
                        "text" => "📂Открыть",
                        "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=route&hide_menu#/s/upload"
                        ]
                    ],
                ],

            ]);
    }

    public function uploadAnyKindOfMedia(...$data)
    {
        $caption = $data[2] ?? null;
        $doc = $data[3] ?? null;
        $type = $data[4] ?? "document";

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Данная опция доступна только персоналу бота!");
            return;
        }

        $docToSend = $doc->file_id ?? null;

        $bot = BotManager::bot()->getSelf();

        $media = \App\Models\BotMedia::query()->updateOrCreate([
            'bot_id' => $bot->id,
            'bot_user_id' => $botUser->id,
            'file_id' => $docToSend,
        ], [
            'caption' => $caption,
            'type' => $type
        ]);

        BotManager::bot()
            ->sendMessage(
                $botUser->telegram_chat_id,
                "Медиа-файл добавлен в медиа пространство бота с идентификатором: <b>#$media->id</b>\n<em>$docToSend</em>\nдля просмотра доступных медиа используйте /media ");

    }

    public function generateOrderTopics(...$data){
        $threads = [
                [
                    "title"=> 'Отзывы',
                    "key"=> 'reviews',
                ],
                [
                    "title"=> 'Начисление cashback',
                    "key"=> 'cashback',

                ],
                [
                    "title"=> 'Вопросы',
                    "key"=> 'questions',

                ],
                [
                    "title"=> 'Конкурсы',
                    "key"=> 'actions',

                ],
                [
                    "title"=> 'Заказы',
                    "key"=> 'orders',

                ],
                [
                    "title"=> 'Вывод средств',
                    "key"=> 'ask-money',

                ],
                [
                    "title"=> 'Доставка',
                    "key"=> 'delivery',

                ],
                [
                    "title"=> 'Ответы',
                    "key"=> 'response',

                ],
                [
                    "title"=> 'Обратная связь',
                    "key"=> 'callback',

                ]
            ];

        $bot = BotManager::bot()->getSelf();

        return BusinessLogic::bots()
            ->setBot($bot ?? null)
            ->createBotTopics($threads);
    }

    public function saveAsOrderChannel(...$data){
        $bot = BotManager::bot()->getSelf();

        $bot->order_channel = $data[0]->chat->id;
        $bot->save();

        BotManager::bot()
            ->replyInlineKeyboard("Идентификатор чата" . ($data[0]->chat->id ?? 'не указан') . "- успешно сохранен как канал для заказов. Теперь создайте топики если это необходимо!",
                [
                    [
                        ["text" => "Сгенерировать топики","callback_data"=>"/generate_order_topics"]
                    ],
                ],
                $data[0]->message_thread_id ?? null,
            );


    }

    public function saveAsMainChannel(...$data){
        $bot = BotManager::bot()->getSelf();

        $bot->main_channel = $data[0]->chat->id;
        $bot->save();

        BotManager::bot()
            ->reply("Идентификатор чата" . ($data[0]->chat->id ?? 'не указан') . "- успешно сохранен как публичный канал для новостей!",
                $data[0]->message_thread_id ?? null);


    }

    public function getMyId(...$data)
    {
        BotManager::bot()
            ->replyInlineKeyboard("Ваш чат id: <pre><code>" . ($data[0]->chat->id ?? 'не указан') . "</code></pre>\nИдентификатор топика: " . ($data[0]->message_thread_id ?? 'Не указан'),
                [
                    [
                        ["text" => "Сохранить как канал заказов","callback_data"=>"/save_as_order_channel"]
                    ],
                    [
                        ["text" => "Сохранить как публичный канал","callback_data"=>"/save_as_main_channel"]
                    ]
                ],
                $data[0]->message_thread_id ?? null,
            );
    }

    public function resetAllBotUsers(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        if (!$botUser->is_admin) {
            BotManager::bot()
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $value = $data[3] ?? 'no';

        if ($value != 'yes') {
            BotManager::bot()
                ->reply("Впишите <b>yes</b> - удалить все данные в боте о пользователях");
            return;
        }

        BotManager::bot()
            ->replyAction()
            ->reply("Бот будет очищен через 3...2...1...")
            ->replyAction()
            ->reply("Бот очищен");

        BusinessLogic::botUsers()
            ->setBotUser($botUser)
            ->setBot($bot)
            ->resetAllBotUsers();


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

    /**
     * @throws \Exception
     */
    public function startWithParam(...$data)
    {
        BotManager::bot()->stopBotDialog();

        $startCommand = $data[3] ?? null;
        StartCodesService::bot()->handler($startCommand);

        if (is_null($startCommand))
            BotManager::bot()->pushCommand("/start");

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

        if (!$botUser->is_admin) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $paymentUrl = env("PAYMENT_BOT_SERVICE_URL") ?? null;

        if (is_null($paymentUrl)) {
            BotManager::bot()->reply("Сервис оплаты временно недоступен!");
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
        $tmpBotId = (str_repeat("0", 10 - strlen($bot->id))) . $bot->id;
        $tmpBotUserId = (str_repeat("0", 10 - strlen($botUser->id))) . $botUser->id;


        foreach ($values as $value) {

            $amount = (str_repeat("0", 10 - strlen($value))) . $value;
            $bcryptLink = base64_encode("005U" . $tmpBotUserId . "B" . $tmpBotId . "A" . $amount);
            $url = "$paymentUrl?start=$bcryptLink";

            $row[] = ["text" => "$value ₽", "url" => $url];

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

        $amountWeek = (str_repeat("0", 10 - strlen($weekTaxFee))) . $weekTaxFee;
        $bcryptLink = base64_encode("005U" . $tmpBotUserId . "B" . $tmpBotId . "A$amountWeek");
        $urlWeek = "$paymentUrl?start=$bcryptLink";

        $amountMonth = (str_repeat("0", 10 - strlen($monthTaxFee))) . $monthTaxFee;
        $bcryptLink = base64_encode("005U" . $tmpBotUserId . "B" . $tmpBotId . "A$amountMonth");
        $urlMonth = "$paymentUrl?start=$bcryptLink";

        $amountHalfYear = (str_repeat("0", 10 - strlen($halfYearTaxFee))) . $halfYearTaxFee;
        $bcryptLink = base64_encode("005U" . $tmpBotUserId . "B" . $tmpBotId . "A$amountHalfYear");
        $urlHalfYear = "$paymentUrl?start=$bcryptLink";

        $amountYear = (str_repeat("0", 10 - strlen($yearTaxFee))) . $yearTaxFee;
        $bcryptLink = base64_encode("005U" . $tmpBotUserId . "B" . $tmpBotId . "A$amountYear");
        $urlYear = "$paymentUrl?start=$bcryptLink";

        $keyboard = [
            [
                ["text" => "Неделя $weekTaxFee ₽", "url" => "$urlWeek"],
                ["text" => "Месяц $monthTaxFee ₽", "url" => "$urlMonth"],

            ],

            [
                ["text" => "Пол года $halfYearTaxFee ₽", "url" => "$urlHalfYear"],
                ["text" => "Год $yearTaxFee ₽", "url" => "$urlYear"],
            ],


        ];

        BotManager::bot()->replyInlineKeyboard("или согласно вашему тарифу:", $keyboard);

    }

    public function sendReview(...$data)
    {

        $value = $data[3] ?? 0;

        $botUser = BotManager::bot()
            ->currentBotUser();

        $emojis = ["😡", "😕", "😐", "🙂", "😁"];


        $name = BotMethods::prepareUserName($botUser);

        $tgId = $botUser->telegram_chat_id ?? '-';
        $phone = $botUser->phone ?? 'Телефон не указан';


        $bot = BotManager::bot()->getSelf();

        BotManager::bot()
            ->sendMessage($botUser->telegram_chat_id, "Спасибо! Ваш отзыв учтен!");

        $thread = $bot->topics["reviews"] ?? null;
        /*
                if ($value <= 2)*/
        BotManager::bot()
            ->sendMessage($bot->order_channel ?? null,
                "#отзыв\nПользователь $name ($tgId, $phone) оставил оценку за обслуживание " . ($emojis[$value] ?? "😡") . "!",
                $thread
            );

        $messageId = $data[0]->message_id ?? null;

        if (!is_null($messageId))
            BotManager::bot()
                ->editMessageText(
                    $botUser->telegram_chat_id,
                    $messageId,
                    "Благодарим вас за вашу оценку💖"
                )
                ->editInlineKeyboard(
                    $botUser->telegram_chat_id,
                    $messageId,
                    [
                        [
                            ["text" => "📢Написать текстовый отзыв с фото", "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=route&hide_menu#/s/feedback"
                            ]],
                        ],
                    ]);

        /*     if ($value==4){
                 BotMethods::bot()
                     ->whereBot($bot)
                     ->sendInlineKeyboard(
                         $botUser->telegram_chat_id,
                         "Оставьте официанту чаевые CashBack-ом (От суммы начисления Вам)", [
                             [
                                 ["text" => "0%", "callback_data" => "/send_tips 0"],
                                 ["text" => "5%", "callback_data" => "/send_tips 1"],
                                 ["text" => "10%", "callback_data" => "/send_tips 2"],
                                 ["text" => "20%", "callback_data" => "/send_tips 3"],
                                 ["text" => "30%", "callback_data" => "/send_tips 4"],
                             ]
                         ]
                     );
             }*/
    }

    public function sendTips(...$data)
    {
        $value = $data[3] ?? 0;
    }

    public function successCompleteOrder(...$data){
        $bot = BotManager::bot()->getSelf();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $data[3] ?? null)
            ->first();

        if (is_null($botUser)) {
            BotManager::bot()
                ->reply("Пользователь не найден");
            return;
        }

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->orderBy("created_at", "DESC")
            ->first();

        if (is_null($order)) {
            BotManager::bot()
                ->reply("Заказ не найден");
            return;
        }

        if ($order->status != OrderStatusEnum::NewOrder->value){
            BotManager::bot()
                ->reply("❗Данный заказ уже готов к выдаче❗");
            return;
        }

        $order->status = OrderStatusEnum::InDelivery->value;
        $order->save();

        $channel = $bot->main_channel ?? null;

        $products = "нет продуктов";
        if (!empty($order->product_details)) {

            $products = "";

            foreach ($order->product_details as $detail) {
                $detail = (object)$detail;
                if (is_array($detail->products)) {
                    foreach ($detail->products as $product) {
                        $product = (object)$product;
                        $products .= "$product->title x$product->count = $product->price ₽\n";
                    }

                } else
                    $products .= "Текст заказа: $detail->products\n";

            }
        }


        $text = "<em>$products</em>\nДата заказа: " . Carbon::parse($order->created_at)
                ->format("Y-m-d H:i:s");

      /*  if (!is_null($channel)) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $channel,
                    "✅Заказ <b>№$order->id</b> готов к выдаче:\n\n$text"
                );

            BotManager::bot()
                ->reply("Операция выполнена успешно!");

        }*/

        $botUser = BotUser::query()
            ->find($order->customer_id);


        if (is_null($botUser)) {
            return;
        }

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $botUser->telegram_chat_id,
                "✅Ваш заказ <b>№$order->id</b> готов и ожидает вас. Написать отзыв можно в нашем канале!"
            );

    }

    public function sendToDelivery(...$data){
        $bot = BotManager::bot()->getSelf();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $data[3] ?? null)
            ->first();

        if (is_null($botUser)) {
            BotManager::bot()
                ->reply("Пользователь не найден");
            return;
        }

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->orderBy("created_at", "DESC")
            ->first();

        if (is_null($order)) {
            BotManager::bot()
                ->reply("Заказ не найден");
            return;
        }

        if ($order->status != OrderStatusEnum::NewOrder->value){
            BotManager::bot()
                ->reply("❗Данный заказ уже передан на доставку❗");
            return;
        }

        $order->status = OrderStatusEnum::InDelivery->value;
        $order->save();

        $channel = $bot->main_channel ?? null;

        $products = "нет продуктов";
        if (!empty($order->product_details)) {

            $products = "";

            foreach ($order->product_details as $detail) {
                $detail = (object)$detail;
                if (is_array($detail->products)) {
                    foreach ($detail->products as $product) {
                        $product = (object)$product;
                        $products .= "$product->title x$product->count = $product->price ₽\n";
                    }

                } else
                    $products .= "Текст заказа: $detail->products\n";

            }
        }


        $text = "<em>$products</em>\nДата заказа: " . Carbon::parse($order->created_at)
                ->format("Y-m-d H:i:s");

     /*   if (!is_null($channel)) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $channel,
                    "✅Заказ <b>№$order->id</b> доставляется клиенту:\n\n$text"
                );

            BotManager::bot()
                ->reply("Операция выполнена успешно!");

        }*/

        $botUser = BotUser::query()
            ->find($order->customer_id);


        if (is_null($botUser)) {
            return;
        }

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $botUser->telegram_chat_id,
                "Ваш заказ <b>№$order->id</b> передан на доставку. Написать отзыв можно в нашем канале!"
            );

    }
    /**
     * @throws ValidationException
     */
    public function autoSendCashBack(...$data)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $data[3] ?? null)
            ->first();

        if (is_null($botUser)) {
            BotManager::bot()
                ->reply("Пользователь не найден");
            return;
        }

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->orderBy("created_at", "DESC")
            ->first();

        if (is_null($order)) {
            BotManager::bot()
                ->reply("Заказ не найден");
            return;
        }

        if (($order->is_cashback_crediting ?? true) === true){
            BotManager::bot()
                ->reply("❗По данному заказу уже был начислен автоматический CashBack❗");
            return;
        }

        $order->is_cashback_crediting = true;
        $order->save();

        $admin = BotManager::bot()->currentBotUser();

        BusinessLogic::administrative()
            ->setBot($bot)
            ->setBotUser($admin)
            ->addCashBack([
                "user_telegram_chat_id" =>$botUser->telegram_chat_id,
                "amount" => $order->summary_price,
                "info" => "Автоматическое начисление CashBack после заказа",
                "need_user_review"=>true
            ]);

        BotManager::bot()
            ->reply("Операция выполнена успешно!");
    }

    private function mediaPrint($tmp, $media, $type = null)
    {

        if (count($media) == 0) {

            $tmp .= "Медиа-файлы не найдены!";
            BotManager::bot()
                ->reply($tmp);
            return;
        }


        $keyboard = [];
        $rowTmpKeyboard = [];
        $index = 1;
        foreach ($media as $item) {

            $tmp .= "#$item->id " . ($item->caption ?? 'Описание не указано') . "\n";

            if ($index % 4 != 0) {
                $rowTmpKeyboard[] = [
                    "text" => "#" . $item->id,
                    "callback_data" => "/show_media_file $item->id"
                ];
            } else {
                $rowTmpKeyboard[] = [
                    "text" => "#" . $item->id,
                    "callback_data" => "/show_media_file $item->id"
                ];

                $keyboard[] = $rowTmpKeyboard;
                $rowTmpKeyboard = [];
            }

            $index++;
        }


        if (count($rowTmpKeyboard) > 0) {
            $keyboard[] = $rowTmpKeyboard;
        }

        if (!is_null($type))
            $keyboard[] = [
                ["text" => "Удалить все медиа в группе", "callback_data" => "/remove_all_media_file $type"]
            ];

        BotManager::bot()
            ->replyInlineKeyboard("$tmp", $keyboard);
    }

    public function getNotes(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $notes = BotNote::query()
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->orderBy("created_at", "DESC")
            ->get();

        $tmp = "Список доступных заметок:\n";

        if (count($notes) == 0) {

            $tmp .= "Заметки не найдены!";
            BotManager::bot()
                ->reply($tmp);
            return;
        }


        $keyboard = [];
        $rowTmpKeyboard = [];
        $index = 1;
        foreach ($notes as $item) {
            $tmp .= "#$item->id " . ($item->text ?? 'Описание не указано') . "\n";

            if ($index % 4 != 0) {
                $rowTmpKeyboard[] = [
                    "text" => "#" . $item->id . "❌",
                    "callback_data" => "/remove_notes $item->id"
                ];
            } else {
                $rowTmpKeyboard[] = [
                    "text" => "#" . $item->id . "❌",
                    "callback_data" => "/remove_notes $item->id"
                ];

                $keyboard[] = $rowTmpKeyboard;
                $rowTmpKeyboard = [];
            }

            $index++;
        }


        if (count($rowTmpKeyboard) > 0) {
            $keyboard[] = $rowTmpKeyboard;
        }

        $keyboard[] = [[
            "text" => "🗑️ Удалить все записи",
            "callback_data" => "/clear_all_notes"
        ]];

        BotManager::bot()
            ->replyInlineKeyboard("$tmp", $keyboard);

    }

    public function getMedia(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        Log::info(print_r($bot->toArray(), true));
        $media = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where(function ($q) {
                $q->where("type", "video")
                    ->orWhere("type", "video_note");
            })
            ->get() ?? [];
        Log::info(print_r($media->toArray(), true));
        if (count($media) > 0) {
            $tmp = "Список доступных видео в медиа контенте:\n";
            $this->mediaPrint($tmp, $media, "video");
        }

        $media = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where("type", "photo")
            ->get();

        if (count($media) > 0) {
            $tmp .= "Список доступных фото в медиа контенте:\n";
            $this->mediaPrint($tmp, $media, "photo");
        }

        $media = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where("type", "document")
            ->get() ?? [];

        if (count($media) > 0) {
            $tmp = "Список доступных документов в медиа контенте:\n";
            $this->mediaPrint($tmp, $media, "document");
        }

        $media = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where("type", "audio")
            ->get() ?? [];

        if (count($media) > 0) {
            $tmp = "Список доступных аудио-файлов в медиа контенте:\n";
            $this->mediaPrint($tmp, $media, "audio");
        }

        $media = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where("type", "voice")
            ->get() ?? [];

        if (count($media) > 0) {
            $tmp = "Список доступных голосовых-файлов в медиа контенте:\n";
            $this->mediaPrint($tmp, $media, "voice");
        }

    }

    public function showDocument(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $id = $data[3] ?? 0;

        $document = Documents::query()
            ->where("bot_id", $bot->id)
            ->where("id", $id)
            ->first();

        if (is_null($document)) {
            BotManager::bot()
                ->reply("Файл не найден!");
            return;
        }

        $keyboard = [
            [
                ["text" => "Подтвердить", "callback_data" => "/accept_verified_document $document->id"],
                ["text" => "Отклонить", "callback_data" => "/decline_verified_document $document->id"]
            ]
        ];

        BotManager::bot()
            ->replyDocumentWithKeyboard($document->title ?? 'Не указан', $document->file_id, $keyboard);
    }

    public function acceptVerifiedDocument(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $id = $data[3] ?? 0;

        $document = Documents::query()
            ->with(["botUser"])
            ->where("bot_id", $bot->id)
            ->where("id", $id)
            ->first();

        if (is_null($document)) {
            BotManager::bot()
                ->reply("Файл не найден!");
            return;
        }

        $document->verified_at = Carbon::now();
        $document->save();

        $this->changeDeliverymanStatus($bot, $document->botUser);

        $thread = $bot->topics["questions"] ?? null;
        $channel = $bot->order_channel ?? null;

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $document->botUser->telegram_chat_id,
                "Документ " . ($document->title ?? 'Без названия') . " одобрен администратором"
            )
            ->sendMessage($channel, "Проверен и одобрен документ #$document->id " . ($document->title ?? 'Без названия'), $thread);

    }

    public function declineVerifiedDocument(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $id = $data[3] ?? 0;

        $document = Documents::query()
            ->with(["botUser"])
            ->where("bot_id", $bot->id)
            ->where("id", $id)
            ->first();

        if (is_null($document)) {
            BotManager::bot()
                ->reply("Файл не найден!");
            return;
        }

        $document->verified_at = null;
        $document->save();

        $thread = $bot->topics["questions"] ?? null;
        $channel = $bot->order_channel ?? null;

        BotMethods::bot()
            ->whereBot($bot)
            ->sendMessage(
                $document->botUser->telegram_chat_id,
                "Документ " . ($document->title ?? 'Без названия') . " отклонен администратором"
            )
            ->sendMessage($channel, "Проверен и отклонен документ #$document->id " . ($document->title ?? 'Без названия'), $thread);

    }

    protected function changeDeliverymanStatus($bot, $botUser)
    {
        if (is_null($botUser) || is_null($bot))
            return;

        $documents = Documents::query()
            ->where("bot_user_id", "$botUser->id")
            ->get();

        $success = false;
        foreach ($documents as $document) {
            $success &= !is_null($document->verified_at);
        }

        $thread = $bot->topics["questions"] ?? null;
        $channel = $bot->order_channel ?? null;

        if ($botUser->is_deliveryman && !$success) {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Внимание!Вас разжаловали из доставщиков по причине некорректности документов"
                );
        }

        $botUser->is_deliveryman = $success;
        $botUser->save();

        $userName = BotMethods::prepareUserName($botUser);

        if ($success)
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Внимание!Вас назначили доставщиком"
                )
                ->sendMessage($channel, "Были проверены и одобрены все документы кандидата $userName в доставщики.", $thread);

    }

    public function showMediaFile(...$data)
    {

        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $id = $data[3] ?? 0;


        $media = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where("id", $id)
            ->first();

        if (is_null($media)) {
            BotManager::bot()
                ->reply("Файл не найден!");
            return;
        }

        $keyboard = [
            [
                ["text" => "Удалить файл", "callback_data" => "/remove_media_file $media->id"]
            ]
        ];

        if ($media->type == "photo")
            BotManager::bot()
                ->replyPhoto($media->caption ?? null, $media->file_id, $keyboard);

        if ($media->type == "audio")
            BotManager::bot()
                ->replyAudio($media->caption ?? null, $media->file_id, $keyboard);

        if ($media->type == "document")
            BotManager::bot()
                ->replyDocumentWithKeyboard($media->caption ?? null, $media->file_id, $keyboard);

        if ($media->type == "video")
            BotManager::bot()
                ->replyVideo($media->caption ?? null, $media->file_id, $keyboard);

        if ($media->type == "video_note")
            BotManager::bot()
                ->replyVideoNote($media->file_id, $keyboard);

    }

    public function clearAllNotes(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();


        $notes = BotNote::query()
            ->where("bot_id", $bot->id)
            ->where("bot_user_id", $botUser->id)
            ->get();

        if (empty($notes)) {
            BotManager::bot()
                ->reply("Заметка не найдена!");
            return;
        }

        foreach ($notes as $note) {
            $note->delete();
        }


        BotManager::bot()
            ->reply("Заметки очищены");
    }

    public function removeNotes(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $id = $data[3] ?? 0;

        $note = BotNote::query()
            ->where("bot_id", $bot->id)
            ->where("id", $id)
            ->first();

        if (is_null($note)) {
            BotManager::bot()
                ->reply("Заметка не найдена!");
            return;
        }

        $note->delete();
        BotManager::bot()
            ->replyInlineKeyboard("Заметка успешно удалена", [
                [
                    ["text" => "Показать оставшиеся заметки", "callback_data" => "/notes"]
                ]
            ]);
    }

    public function removeAllMediaFileByType(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $type = $data[3] ?? 0;

        $medias = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where("type", $type)
            ->get();

        if (count($medias) == 0) {
            BotManager::bot()
                ->reply("Группа файлов пуста");
            return;
        }

        foreach ($medias as $media)
            $media->delete();

        BotManager::bot()
            ->reply("Файлы успешно удалены");
    }

    public function removeMediaFile(...$data)
    {
        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->reply("У вас недостаточно прав для выполнения данной команды");
            return;
        }

        $bot = BotManager::bot()->getSelf();

        $id = $data[3] ?? 0;

        $media = BotMedia::query()
            ->where("bot_id", $bot->id)
            ->where("id", $id)
            ->first();

        if (is_null($media)) {
            BotManager::bot()
                ->reply("Файл не найден!");
            return;
        }

        $media->delete();
        BotManager::bot()
            ->replyInlineKeyboard("Файл успешно удален", [
                [
                    ["text" => "Показать оставшиеся файлы", "callback_data" => "/media"]
                ]
            ]);
    }


    public function helpBot(...$data)
    {
        BotManager::bot()
            ->reply("Раздел помощи временно недоступен, но скоро будет работать на полную силу!");
    }

    public function aboutBot(...$data)
    {
        $bot = BotManager::bot()->getSelf();
        BotManager::bot()
            ->replyPhoto("Хочешь такой же бот для своего бизнеса? ",
                InputFile::create(public_path() . "/images/cashman.jpg"),
                [
                    [
                        [
                            "text" => "🔥Перейти в нашего бота для заявок",
                            "url" => "https://t.me/cashman_dn_bot"
                        ]
                    ],
                    [
                        [
                            "text" => "\xF0\x9F\x8D\x80Написать в тех. поддержку",
                            "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route#/about"
                            ]
                        ],
                    ],

                ]
            );
    }
}
