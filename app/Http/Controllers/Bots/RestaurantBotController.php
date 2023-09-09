<?php

namespace App\Http\Controllers\Bots;

use App\Classes\TextTrait;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Company;
use App\Models\Location;
use App\Models\ReferralHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class RestaurantBotController extends Controller
{
    use TextTrait;

    public function startWithParam(...$data)
    {
        BotManager::bot()->stopBotDialog();

        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        $message = $bot->welcome_message ?? null;

        Log::info("startWithParam data".print_r($data[3], true));

        if (!is_null($data[3])) {
            $pattern_simple = "/([0-9]{3})([0-9]+)/";
            $pattern_extended = "/([0-9]{3})([0-9]{8,10})S([0-9]+)/";

            $string = base64_decode($data[3]);

            preg_match_all(strlen($string)<=13 ? $pattern_simple : $pattern_extended, $string, $matches);

            $code = $matches[1][0] ?? null;
            $request_telegram_chat_id = $matches[2][0] ?? null;
            $slug_id = $matches[3][0] ?? 'route';


            Log::info("request_telegram_chat_id".$request_telegram_chat_id);

            //$qrCode = new QRCodeHandler($code, $request_user_id);

            if ($botUser->is_admin) {
                Log::info("startWithParam is_admin $code $request_telegram_chat_id $slug_id");
                switch ($code) {
                    default:
                    case "001":
                        $text = "Основная административная панель";
                        $path =  env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route&user=$request_telegram_chat_id#/admin-main";
                        break;

                    case "002":
                        $text = "Административное меню системы бонусных накоплений";
                        $path =  env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slug_id&user=$request_telegram_chat_id#/admin-bonus-product";
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

            $userBotUser = BotUser::query()
                ->where("telegram_chat_id", $request_telegram_chat_id)
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

    public function firstStart()
    {
        BotManager::bot()->stopBotDialog();

        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        $message = $bot->welcome_message ?? null;

        if ($botUser->is_admin) {
            BotManager::bot()
                ->sendReplyMenu((is_null($message) ? "" : $message) . "<b>Режим администратора</b>", "main_menu_restaurant_3");
            return;
        }

        /*   BotManager::bot()->reply("test");


            BotManager::bot()->replyInlineKeyboard("TESSSST1",[
                [
                    ["text"=>"Action 1","callback_data"=>"/action"],
                    ["text"=>"Action 2","callback_data"=>"/action"],
                    ["text"=>"Action 3","callback_data"=>"/action"],
                ],
                [
                    ["text"=>"Action 3","callback_data"=>"/action"],
                    ["text"=>"Action 4","callback_data"=>"/action"],
                ],

            ]);

            BotManager::bot()->replyKeyboard("TESSSST2",[
               [
                   ["text"=>"Action 1"],
                   ["text"=>"Action 2"],
               ],
                [
                    ["text"=>"Action 1"],
                    ["text"=>"Action 2"],
                ],
                [
                    ["text"=>"Action 1"],
                    ["text"=>"Action 2"],
                ],
                [
                    ["text"=>"Action 1"],
                    ["text"=>"Action 2"],
                ],
                [
                    ["text"=>"Action 1"],
                    ["text"=>"Action 2"],
                ],
                [
                    ["text"=>"Action 1"],
                    ["text"=>"Action 2"],
                ],
            ]);*/

        BotManager::bot()
            ->sendReplyMenu((is_null($message) ? "" : $message),
                !$botUser->is_vip ?
                    "main_menu_restaurant_1" :
                    "main_menu_restaurant_2");
    }

    public function start()
    {
        $botUser = BotManager::bot()->currentBotUser();

        if ($botUser->is_admin) {
            BotManager::bot()
                ->sendReplyMenu("Главное меню (Режим администратора)", "main_menu_restaurant_3");
            return;
        }

        BotManager::bot()
            ->sendReplyMenu("Главное меню",
                !$botUser->is_vip ?
                    "main_menu_restaurant_1" :
                    "main_menu_restaurant_2");
    }

    public function inviteFriends()
    {
        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $botDomain = $bot->bot_domain;

        $companyDomain = $bot->company->slug;

        $qr = "https://t.me/$botDomain?start=" .
            base64_encode("001" . BotManager::bot()->getCurrentChatId());

        $friendCount = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->count();

        \App\Facades\BotManager::bot()
            ->replyPhoto("Вы пригласили <b>$friendCount друзей</b>\nВы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой",
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));


        $path = storage_path("app/public") . "/companies/$companyDomain/" . ($bot->image ?? 'noimage.jpg');
        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/cashman.jpg"
        );

        \App\Facades\BotManager::bot()
            ->replyPhoto("Перешли эту ссылку друзьям:\n<a href=\"$qr\">$qr</a>\n<span class=\"tg-spoiler\">И получи бонусные баллы <strong>CashBack</strong></span>",
                $file
            );

        BotManager::bot()
            ->sendReplyMenu("Пригласить друзей",
                "menu_level_2_restaurant_5");
    }

    public function location()
    {
        $bot = BotManager::bot()->getSelf();

        $company = Company::query()
            ->with(["locations"])
            ->where("id", $bot->company_id)
            ->first();

        $companySlug = $company->slug;

        if (is_null($company))
            BotManager::bot()
                ->reply("Что-то пошло не так!");

        $companyText =
            "<b>" . ($company->title ?? 'Без названия') . "</b>\n" .
            "<em>" . ($company->description ?? 'Без описания') . "</em>\n\n" .
            "<b>Контактная информация</b>\n\n";

        if (!empty($company->phones)) {
            $companyText .= "<b>Телефоны для связи</b>\n";

            foreach ($company->phones as $phone)
                $companyText .= "\xF0\x9F\x94\xB8 $phone\n";
        }

        if (!empty($company->links)) {
            $companyText .= "<b>Интернет ресурсы</b>\n";
            foreach ($company->links as $link)
                $companyText .= "\xF0\x9F\x94\xB8 $link\n";
        }

        $companyText .= "Менеджер: <b>" . ($company->manager ?? 'Не указан') . " </b>\n";
        $companyText .= "Почта: <b>" . ($company->email ?? 'Не указано') . " </b>\n";

        $companyText .= "Наше расположение: <b>" . ($company->address ?? 'Не указано') . " </b>\n\n";

        if (!empty($company->schedule)) {
            $companyText .= "<b>График работы</b>\n";

            foreach ($company->schedule as $item)
                $companyText .= "$item\n";
        }


        //BotManager::bot()->reply($companyText);

        $keyboard = [];
        if (!is_null($company->locations)) {
            foreach ($company->locations as $location) {
                if ($location->is_active)
                    $keyboard[] = [
                        [
                            "text" => $location->address, "callback_data" => "/location $location->id"
                        ]
                    ];
            }

        }

        $path = storage_path("app/public") . "/companies/$companySlug/" . $company->image;
        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/cashman.jpg"
        );

        if (is_null($company->image))
            BotManager::bot()
                ->replyInlineKeyboard($companyText, $keyboard);
        else
            BotManager::bot()
                ->replyPhoto($companyText,
                    $file,
                    $keyboard
                );

        \App\Facades\BotManager::bot()
            ->sendReplyMenu("Наше расположение", "menu_level_2_restaurant_4");
    }

    private function printLocation($locationId)
    {
        $location = Location::query()
            ->with(["company"])
            ->where("id", ($locationId ?? null))
            ->first();


        if (is_null($location))
            \App\Facades\BotManager::bot()
                ->reply("К сожалению, данная локация не содержит информации о себе");

        if (!$location->is_active)
            \App\Facades\BotManager::bot()
                ->reply("К сожалению, данная локация временно недоступна");


        $locationText =
            "Мы расположены по адресу <b>" . ($location->address ?? "Не указано") . "</b>\n" .
            "<em>" . ($location->description ?? "Не задано") . "</em>\n" .
            ($location->can_booking ? "<b>Через данного бота вы можете забронировать у нас столик</b>" : "");


        if (!is_null($location->images)) {


            if (count($location->images) > 1) {
                $media = [];
                foreach ($location->images as $image) {
                    $media[] = [
                        "media" => env("APP_URL") . "/images/" . $location->company->slug . "/" . $image,
                        "type" => "photo",
                    ];
                }

                BotManager::bot()->replyMediaGroup($media);
            } else if (count($location->images) === 1) {
                BotManager::bot()->replyPhoto("Фотографии нашего заведения",
                    InputFile::create(storage_path("app/public") . "/companies/" . $location->company->slug . "/" . $location->images[0]),

                );

            }
        }


        if ($location->can_booking)
            BotManager::bot()
                ->sendInlineMenu("$locationText",
                    "booking_table_1");
        else
            BotManager::bot()
                ->reply($locationText);

        if (!is_null($location->lat) && !is_null($location->lon))
            BotManager::bot()->replyLocation($location->lat, $location->lon);
    }

    public function locationInfo(...$data)
    {
        $this->printLocation($data[2] ?? null);
    }

    public function menu()
    {

        $bot = BotManager::bot()->getSelf();


        if (count($bot->imageMenus) > 1) {

            $msgs = "";
            $media = [];
            foreach ($bot->imageMenus as $image) {
                if (!is_null($image->title) && !is_null($image->description))
                    $msgs .= "<b>" . $image->title . "</b>\n" . $image->description . "\n";

                $media[] = [
                    "media" => env("APP_URL") . "/images/" . $bot->company->slug . "/" . $image->image,
                    "type" => "photo",
                    "caption" => $image->title ?? env("APP_URL") . "/images/" . $bot->company->slug . "/" . $image->image
                ];
            }

            BotManager::bot()->replyMediaGroup($media);

            $menu = $bot->imageMenus[0];

            if (!is_null($menu->info_link))
                BotManager::bot()
                    ->replyInlineKeyboard($msgs,
                        [
                            [
                                ["text" => "Ссылка на меню", "url" => $menu->info_link]
                            ]
                        ]);
            else
                BotManager::bot()
                    ->reply($msgs);

        } else if (count($bot->imageMenus) === 1) {


            $menu = $bot->imageMenus[0];
            $keyboard = [];
            if (!is_null($menu->info_link))
                $keyboard = [
                    [
                        ["text" => "Ссылка на меню", "url" => $menu->info_link]
                    ]
                ];

            if (!is_null($menu->image))
                BotManager::bot()->replyPhoto("<b>" . $menu->title . "</b>\n" . $menu->description,
                    InputFile::create(storage_path("app/public") . "/companies/" . $bot->company->slug . "/" . $menu->image),
                    $keyboard
                );

            if (!is_null($menu->info_link) && is_null($menu->image))
                BotManager::bot()
                    ->replyPhoto($menu->title . "\n" . $menu->description,
                        InputFile::create(storage_path("app/public") . "/companies/" . $bot->company->slug . "/" . $bot->company->image),
                        $keyboard);
        }


        BotManager::bot()
            ->sendReplyMenu("Наше меню", "menu_level_3_restaurant_1");


    }

    public function establishments()
    {
        $bot = BotManager::bot()->getSelf();

        $locations = $bot->company->locations;
        if (empty($locationso)) {
            BotManager::bot()
                ->replyPhoto("У данного заведения нет отдельных локаций",
                    InputFile::create(public_path() . "/images/cashman.jpg"));
            return;
        }
        foreach ($locations as $location)
            $this->printLocation($location->id ?? null);
    }

    public function aboutUs()
    {

        $bot = BotManager::bot()->getSelf();

        $keyboard = [];


        if (!empty($bot->social_links)) {
            foreach ($bot->social_links as $item) {
                $item = (object)$item;
                $keyboard[] = [
                    [
                        "text" => $item->title ?? 'Без описания', "url" => $item->url
                    ]
                ];
            }

        }

        $message = ($bot->description ?? "Описание бота не указано") . "\n" .
            ($bot->info_link ?? "Тут будет ссылка на информацию о заведении");

        BotManager::bot()
            ->replyInlineKeyboard($message, $keyboard);
    }

    public function aboutBot()
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
                                "url" => env("APP_URL") . "/callback-form/" . $bot->bot_domain
                            ]
                        ],
                    ],

                ]
            );

    }

    public function vipForm()
    {
        $bot = BotManager::bot()->getSelf();

        \App\Facades\BotManager::bot()
            ->replyPhoto("Заполни эту анкету и получит достук к системе CashBack",
                InputFile::create(public_path() . "/images/cashman2.jpg"),
                [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                            "url" => env("APP_URL") . "/restaurant/vip-form/$bot->bot_domain"
                        ]],
                    ],

                ]);
    }

    public function specialCashBackSystem()
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

    public function callTheWaiter()
    {
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("callTheWaiter", "cashback_buttons_1");
    }

    public function myBudget()
    {
        \App\Facades\BotManager::bot()
            ->sendReplyMenu("Операции над вашим бюджетом", "menu_level_3_restaurant_2");
    }

    public function requestCashBack()
    {
        $bot = BotManager::bot()->getSelf();

        $botUsers = BotUser::query()
            ->where("is_admin", true)
            ->where("is_work", true)
            ->where("bot_id", $bot->id)
            ->get();

        if (count($botUsers) == 0) {
            BotManager::bot()->reply("К сожалению в данный момент нет доступных администраторов!");
            return;
        }

        BotManager::bot()
            ->sendInlineMenu("Меню вызова администратора", "cashback_ask_admin_1");
    }

    public function friendsNetwork()
    {
        \App\Facades\BotManager::bot()
            ->replyPhoto(
                "Раздел \"Сеть друзей\" находится в разработке!",
                InputFile::create(public_path() . "/images/underconstruction.jpg")
            );
    }

    public function bookTable()
    {
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("В открывшемся окне укажите какой именно столик вы хотите забронировать. Администратор заведения в телефонном режиме уточнит у вас информацию.",
                "booking_table_1");
    }

    public function charges()
    {
        $botUser = BotManager::bot()->currentBotUser();

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $botUser->bot_id)
            ->where("user_id", $botUser->user_id)
            ->where("operation_type", 1);

        $tmpCount = $cashBackHistories->count();

        $cashBackHistories = $cashBackHistories
            ->take(10)
            ->skip(0)
            ->get();

        $tmp = "<b>Начисления ($tmpCount операций):</b>\n";

        foreach ($cashBackHistories as $item) {
            $tmp .= "<b>" . $item->amount . "</b> руб уровень <em>" .
                ($item->level ?? 1) . "</em> " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";
        }

        if ($tmpCount > 10)
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($tmp, [
                    [
                        ["text" => "Загрузить еще", "callback_data" => "/more_cashback $botUser->bot_id $botUser->user_id 1 1"]
                    ]
                ]);
        else
            \App\Facades\BotManager::bot()
                ->reply($tmp);
    }

    public function moreCashBackHistory(...$data)
    {

        $botId = $data[2] ?? null;
        $userId = $data[3] ?? null;
        $type = $data[4] ?? null;
        $page = $data[5] ?? null;

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $botId)
            ->where("user_id", $userId)
            ->where("operation_type", $type);

        $tmpCount = $cashBackHistories->count() - $page * 10;

        $cashBackHistories = $cashBackHistories
            ->skip($page * 10)
            ->take(10)
            ->get();

        $tmp = "<b>Списания ($tmpCount операций):</b>\n";

        foreach ($cashBackHistories as $item) {
            $tmp .= "<b>" . $item->amount . "</b> руб " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";
        }


        if ($tmpCount > 10) {
            $page++;
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($tmp, [
                    [
                        ["text" => "Загрузить еще", "callback_data" => "/more_cashback $botId $userId $type $page"]
                    ]
                ]);
        } else
            \App\Facades\BotManager::bot()
                ->reply($tmp);


    }

    public function writeOffs()
    {
        $botUser = BotManager::bot()->currentBotUser();

        $cashBackHistories = CashBackHistory::query()
            ->where("bot_id", $botUser->bot_id)
            ->where("user_id", $botUser->user_id)
            ->where("operation_type", 0);

        $tmpCount = $cashBackHistories->count();

        $cashBackHistories = $cashBackHistories
            ->take(10)
            ->skip(0)
            ->get();

        $tmp = "<b>Списания ($tmpCount операций):</b>\n";

        foreach ($cashBackHistories as $item) {
            $tmp .= "<b>" . $item->amount . "</b> руб " .
                (Carbon::parse($item->created_at)
                    ->format("Y-m-d H:i:s")) . "\n";
        }

        if ($tmpCount > 10)
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($tmp, [
                    [
                        ["text" => "Загрузить еще", "callback_data" => "/more_cashback $botUser->bot_id $botUser->user_id 0 1"]
                    ]
                ]);
        else
            \App\Facades\BotManager::bot()
                ->reply($tmp);
    }

    public function myFriends()
    {
        $botUser = BotManager::bot()->currentBotUser();

        $refs = ReferralHistory::query()
            ->with(["recipient", "recipient.botUser"])
            ->where("user_sender_id", $botUser->user_id)
            ->orderBy("created_at", "DESC")
            ->take(20)
            ->skip(0)
            ->get();

        if (count($refs) === 0) {
            \App\Facades\BotManager::bot()
                ->reply("Вы ни кого не добавили в свою сеть друзей!");
            return;
        }

        $tmp = "<b>Ваш список друзей:</b>\n";
        foreach ($refs as $ref)
            $tmp .= "<b>" . BotMethods::prepareUserName($ref->recipient->botUser) . "</b>\n";


        \App\Facades\BotManager::bot()
            ->reply($tmp);
    }

    public function searchFriends()
    {
        \App\Facades\BotManager::bot()
            ->replyPhoto(
                "Раздел \"Поиск друзей\" находится в разработке!",
                InputFile::create(public_path() . "/images/underconstruction.jpg")
            );
    }

    public function charities()
    {
        \App\Facades\BotManager::bot()
            ->replyPhoto(
                "Раздел \"Благорвторительность\" находится в разработке!",
                InputFile::create(public_path() . "/images/underconstruction.jpg")
            );
    }

}
