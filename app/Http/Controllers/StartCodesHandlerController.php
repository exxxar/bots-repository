<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Models\Bot;
use App\Models\BotPage;
use App\Models\BotUser;
use App\Models\ManagerProfile;
use App\Models\Order;
use App\Models\ReferralHistory;
use App\Models\Table;
use App\Models\TrafficSource;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Style\Tab;

class StartCodesHandlerController extends Controller
{

    public function confirmRegistrationAndLogin(...$data)
    {

        $bot = BotManager::bot()
            ->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $userId = $data[2] ?? null;

        if (is_null($userId)) {
            BotManager::bot()
                ->reply("Упс.. сервис временно недоступен!");
            return;
        }

        $user = User::findOrFail($userId);
        $botUser->user_id = $user->id;
        $botUser->is_manager = true;
        $botUser->save();

        $managerForm = [
            'bot_user_id' => $botUser->id,
            'info' =>null,
            'referral' => null,
            'strengths' => [],
            'weaknesses' => [],
            'educations' => [],
            'social_links' => [],
            'skills' => [],
            'stable_personal_discount' => 0,
            'permanent_personal_discount' => 0,
            'max_company_slot_count' => 1,
            'max_bot_slot_count' => 10,
            'balance' => 3000,
            'verified_at' => null
        ];

        $manager = ManagerProfile::query()->create($managerForm);

        $url = URL::signedRoute('auth.magic', [
            'user' => $user->id,
            'expires' => Carbon::now()->addMinutes(3000)->timestamp,
        ]);

        Log::info("signedRoute $url");

        BotMethods::bot()
            ->whereBot($bot)
            ->sendInlineKeyboard(
                $botUser->telegram_chat_id,
                "Поздравляем! Вы зарегистрировались и теперь вам доступно создание ботов!\n".
                "<b>Сводка</b>:\n".
                "Стартовый баланс: $manager->balance руб. - вы можете распределить его между ботами\n".
                "Персональная скидка: $manager->stable_personal_discount %.\n".
                "Вы можете создать ботов: $manager->max_bot_slot_count\n"
                ,
                [
                    [
                        ["text" => "Панель управления", "url" => $url],
                    ]
                ]
            );

    }

    public function openTableMenu(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $slugId = $data[2] ?? null;
        $tableNumber = $data[3] ?? null;

        if (is_null($slugId) || is_null($tableNumber)) {
            BotManager::bot()
                ->reply("Упс.. сервис временно недоступен!");
            return;
        }

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

            $thread = $bot->topics["orders"] ?? null;

            $adminPath = env("APP_URL") . "/bot-client/simple/%s?slug=%s#/s/admin/tables-manager/%s";

            BotMethods::bot()
                ->whereBot($bot)
                ->sendInlineKeyboard(
                    $bot->order_channel,
                    "Столик №$tableNumber занял новый клиент! Подойдите к нему!",
                    [
                        [
                            ["text" => "🛎️Работа со столиком",
                                "web_app" => [
                                    "url" => sprintf(
                                        $adminPath,
                                        $bot->bot_domain,
                                        $slugId,
                                        $table->id
                                    )
                                ]
                            ],
                        ]
                    ],
                    $thread
                );
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
                                    $slugId
                                )
                            ]
                        ],
                    ]
                ]
            );

    }

    public function paymentAction(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();

        $code = $data[1] ?? null;

        $bot_user_id = $data[2] ?? null;
        $bot_id = $data[3] ?? null;
        $value = intval($data[4] ?? 0);

        if ($code != "005") {
            BotManager::bot()
                ->reply("Ошибка типа данных!");
            return;
        }

        $botUserPayment = BotUser::query()
            ->where("id", $bot_user_id)
            ->first();

        $botPayment = Bot::query()
            ->where("id", $bot_id)
            ->first();

        if (is_null($botPayment) || is_null($botUserPayment)) {
            BotManager::bot()
                ->reply("Ошибка передачи платежных данных!");
            return;
        }

        if (!$botUserPayment->is_admin) {
            BotManager::bot()
                ->reply("У вас нет прав доступа!");
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
            'user_id' => $botUserPayment->user_id,
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
                "CashMan:Оплата", "Оплата услуг сервиса CashMan", $prices, $payload, $providerToken, $currency, $needs, $keyboard,
                $providerData
            );;
    }

    public function slugAction(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();

        $code = $data[1] ?? null;
        $request_id = $data[2] ?? null;
        $slug_id = $data[3] ?? 'route';

        if ($code != "002")
            return;

        $text = "Административное меню системы бонусных накоплений";
        $path = env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slug_id&user=$request_id#/admin-bonus-product";

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

    public function orderAction(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();

        $code = $data[1] ?? null;
        $request_id = $data[2] ?? null;
        $order_id = $data[3] ?? null;

        if ($code != "001")
            return;


        $this->userOrder($request_id, $order_id);


    }

    public function runPageAction(...$data)
    {
        Log::info("UTM SOURCE runPageAction".print_r($data, true));
        $bot = BotManager::bot()
            ->getSelf();

        $botUser = BotManager::bot()
            ->currentBotUser();

        $code = $data[1] ?? null;
        $request_id = $data[2] ?? null;
        $utm = $data[3] ?? null;

        $channel = $bot->order_channel ??
            null;

        TrafficSource::query()->updateOrCreate([
            'bot_id' => $bot->id,
            'bot_user_id' => $botUser->id,
            'comment' => "ссылка с меткой",
            'source' => $utm,
            'is_individual' => false
        ]);


        $this->testReferrals($bot, $request_id);

        if ($botUser->is_admin)
            $this->adminLogic($bot, $request_id, $code);

        if (!is_null($channel))
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage($channel, "Переход в бота из $utm");

        if ($code == "004")
            BotManager::bot()->runPage($request_id);

    }

    protected function adminLogic($bot, $id, $code)
    {
        $requestBotUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $id ?? null)
            ->first();

        $tmpOrderURIId = "";

        if (!is_null($requestBotUser)) {
            $order = Order::query()
                ->where("bot_id", $bot->id)
                ->where("customer_id", $requestBotUser->id)
                ->orderBy("created_at", "DESC")
                ->first();


            if (!is_null($order))
                $tmpOrderURIId = "&order_id=$order->id";
        }

        if ($code == "001" || $code == "003") {
            $text = "Админ панель";
            $path = env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=route&user=$id&hide_menu$tmpOrderURIId#/s/admin/clients";

            $requestKeyboard = [
                [
                    ["text" => "\xF0\x9F\x8E\xB0Управление клиентом",
                        "web_app" => [
                            "url" => $path
                        ]
                    ],

                ],

            ];

            BotManager::bot()->replyInlineKeyboard(
                $text,
                $requestKeyboard
            );
        }

    }

    protected function testReferrals($bot, $id)
    {

        $botUser = BotManager::bot()->currentBotUser();
        if ($botUser->telegram_chat_id == $id) {
            BotManager::bot()
                ->reply(
                    "Вы перешли по своей собственной ссылке... вы, конечно, себе друг, но CashBack достанется кому-то одному..."
                );

            BotManager::bot()
                ->setBot($bot)
                ->pushCommand("/start");
            return;

        }

        $userBotUser = BotUser::query()
            ->where("telegram_chat_id", $id)
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($userBotUser) || is_null($botUser)) {
            BotManager::bot()
                ->setBot($bot)
                ->pushCommand("/start");
            return;
        }

        $ref = ReferralHistory::query()
            ->where("user_sender_id", $userBotUser->user_id ?? null)
            ->where("user_recipient_id", $botUser->user_id ?? null)
            ->where("bot_id", $bot->bot_id)
            ->first();

        if (!is_null($ref))
            return;

        ReferralHistory::query()->create([
            'user_sender_id' => $userBotUser->user_id,
            'user_recipient_id' => $botUser->user_id,
            'bot_id' => $bot->id,
            'activated' => true,
        ]);

        $userName1 = BotMethods::prepareUserName($botUser);
        $userName2 = BotMethods::prepareUserName($userBotUser);

        $botUser->parent_id = $userBotUser->id;
        $botUser->save();

        TrafficSource::query()->updateOrCreate([
            'bot_id' => $bot->id,
            'bot_user_id' => $botUser->id,
            'comment' => "реферальная программа",
            'source' => "$id",
            'is_individual' => true
        ]);

        $path = env("APP_URL") . "/bot-client/simple/%s?slug=route&hide_menu&friend=%s#/s/referral";

        $botUserTelegramChatId = $botUser->telegram_chat_id;

        BotMethods::bot()
            ->whereId($botUser->bot_id)
            ->sendInlineKeyboard(
                $userBotUser->telegram_chat_id,
                "По вашей ссылке перешел пользователь <b>$userName1</b>" .
                "\n<a href='tg://user?id=$botUserTelegramChatId'>Перейти к чату с пользователем</a>\n"
                ,
                [
                    [
                        ["text" => "👨‍👨Узнать о вашем друге",
                            "web_app" => [
                                "url" => sprintf(
                                    $path,
                                    $bot->bot_domain,
                                    $botUser->id,
                                )
                            ]
                        ],
                    ]
                ]

            )
            ->sendInlineKeyboard(
                $botUser->telegram_chat_id,
                "Вас и вашего друга <b>$userName2</b> теперь объединяет еще и бонусная система;)",
                [
                    [
                        ["text" => "👨‍👨Узнать о вашем друге",
                            "web_app" => [
                                "url" => sprintf(
                                    $path,
                                    $bot->bot_domain,
                                    $userBotUser->id,
                                )
                            ]
                        ],
                    ]
                ]
            );


        /*        if (is_null($userBotUser)) {
                    BotManager::bot()->reply("Данный код не корректный!");

                    BotManager::bot()
                        ->setBot($bot)
                        ->pushCommand("/start");
                    return;
                }*/
    }

    public function editPage(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();

        $botUser = BotManager::bot()
            ->currentBotUser();

        if (!$botUser->is_admin) {
            BotManager::bot()
                ->reply("Упс... Вы не администратор");
            return;
        }

        $code = $data[1] ?? null;
        $page_id = $data[2] ?? null;

        $text = "Редактирование страницы";
        $path = env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=route&hide_menu#/s/admin/page-editor/$page_id";

        BotManager::bot()->replyInlineKeyboard(
            $text,
            [
                [
                    ["text" => "\xF0\x9F\x8E\xB0Открыть редактор",
                        "web_app" => [
                            "url" => $path
                        ]
                    ],
                ]
            ]
        );

    }

    public function referralAction(...$data)
    {

        Log::info("отработала referralAction");
        $bot = BotManager::bot()
            ->getSelf();

        $botUser = BotManager::bot()
            ->currentBotUser();

        $code = $data[1] ?? null;
        $request_id = $data[2] ?? null;

        $message = $bot->welcome_message ?? null;

        $this->testReferrals($bot, $request_id);

        if ($botUser->is_admin)
            $this->adminLogic($bot, $request_id, $code);

        if ($code == "004") {
            BotManager::bot()
                ->setBot($bot)
                ->runPage($request_id);
            return;
        }

        if ($code == "005") {
            BotManager::bot()
                ->setBot($bot)
                ->runSlug($request_id);
            return;
        }

        /*      if ($code != "011") {
                  BotManager::bot()->reply($message);
                  return;
              }
      */

        BotManager::bot()->reply($message);

        BotManager::bot()
            ->setBot($bot)
            ->pushCommand("/start");

    }

    private function userOrder($telegramChatId, $orderId)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotUser::query()
            ->where("telegram_chat_id", $telegramChatId)
            ->where("bot_id", $bot->id)
            ->first();

        if (is_null($botUser)) {
            BotManager::bot()
                ->reply("Упс... Клиент не найден");
            return;
        }

        $order = Order::query()
            ->where("bot_id", $bot->id)
            ->where("customer_id", $botUser->id)
            ->where("id", $orderId)
            ->orderBy("updated_at", "DESC")
            ->first();


        if (is_null($order)) {
            BotManager::bot()
                ->reply("Упс... Заказов нет:(");
            return;
        }

        $from = "не указан источник";
        $products = "нет продуктов";
        if (!empty($order->product_details)) {

            $products = "";

            foreach ($order->product_details as $detail) {
                $detail = (object)$detail;
                $from = $detail->from ?? 'Не указано';
                if (is_array($detail->products)) {
                    foreach ($detail->products as $product) {
                        $product = (object)$product;
                        $products .= "$product->title x$product->count = $product->price ₽\n";
                    }

                } else
                    $products .= "Текст заказа: $detail->products\n";

            }
        }


        $statuses = ["Новый заказ", "В процессе доставки", "Завершен", "Отменен"];

        $address = $order->address ?? 'не указан';
        $name = $order->receiver_name ?? 'не указан';
        $phone = $order->receiver_phone ?? 'не указан';
        $status = $statuses[$order->status ?? 0] ?? 'без статуса';

        $note = sprintf("Имя заказчика: %s\nАдрес доставки: %s\nТелефон: %s\nСтатус: %s\n<b>Заметки к заказу:</b> <em>%s</em>\n",
            $name,
            $address,
            $phone,
            $status,
            ($order->delivery_note ?? 'не указана')
        );

        $text = "Заказ #$order->id\nПрислан из $from:\n<em>$products</em>Дата заказа: " . Carbon::parse($order->created_at)
                ->format("Y-m-d H:i:s") . "\n\n<b>Заметка для доставщика:</b>\n$note";


        BotManager::bot()
            ->reply($text);

    }
}
