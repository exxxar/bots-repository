<?php

namespace App\Http\Controllers;

use App\Classes\QRCodeHandler;
use App\Classes\TextTrait;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Company;
use App\Models\Location;
use App\Models\ReferralHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class RestaurantBotController extends Controller
{
    use TextTrait;

    public function startWithParam(...$data)
    {
        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        $message = $bot->welcome_message ?? null;

        if (!is_null($data[2])) {
            $pattern = "/([0-9]{3})([0-9]+)/";

            $string = base64_decode($data[2]);

            preg_match_all($pattern, $string, $matches);

            $code = $matches[1][0] ?? null;
            $request_telegram_chat_id = $matches[2][0] ?? null;

            //$qrCode = new QRCodeHandler($code, $request_user_id);

            if ($botUser->is_admin) {
                $bot_domain = BotManager::bot()->getSelf()->bot_domain;
                BotManager::bot()->replyInlineKeyboard(
                    "Административное меню",
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB0Перейти в админку бота",
                                "web_app" => [
                                    "url" => env("APP_URL") . "/admin/$bot_domain/$request_telegram_chat_id"
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

                $userName = BotMethods::prepareUserName($botUser);

                BotMethods::bot()
                    ->whereId($botUser->bot_id)
                    ->sendMessage(
                        $request_telegram_chat_id,
                        "По вашей ссылке перешел пользователь $userName"
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
            ->sendReplyMenu("Главное меню",
                !$botUser->is_vip ?
                    "main_menu_restaurant_1" :
                    "main_menu_restaurant_2");
    }

    public function firstStart()
    {
        $botUser = BotManager::bot()->currentBotUser();

        $bot = BotManager::bot()->getSelf();

        $message = $bot->welcome_message ?? null;

        if ($botUser->is_admin) {
            BotManager::bot()
                ->sendReplyMenu((is_null($message)?"":"$message \n")."Главное меню (Режим администратора)", "main_menu_restaurant_3");
            return;
        }

        BotManager::bot()
            ->sendReplyMenu((is_null($message)?"":"$message \n")."Главное меню",
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

        $botDomain = $bot->bot_domain;

        $qr = "https://t.me/$botDomain?start=" .
            base64_encode("001" . BotManager::bot()->getCurrentChatId());

        \App\Facades\BotManager::bot()
            ->replyPhoto("Вы пригласили <b>0 друзей</b>\nВы можете пригласить друзей показав им QR код или скопировать реферальную ссылку и поделиться ей в Соц Сетях или других мессенджерах.
Чтобы пригласить с помощью Телеграм, для этого нажмите на стрелочку рядом с ссылкой",
                InputFile::create("https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qr"));


        $file = InputFile::create(
            file_exists(storage_path("app/public") . "/companies/" . ($bot->image ?? 'noimage.jpg')) ?
                storage_path("app/public") . "/companies/" . $bot->image :
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


        if (is_null($company))
            BotManager::bot()
                ->reply("Что-то пошло не так!");

        $companyText =
            "<b>" . ($company->title ?? 'Без названия') . "</b>\n" .
            "<em>" . ($company->description ?? 'Без описания') . "</em>\n\n" .
            "<b>Контактная информация</b>\n\n";

        if (!is_null($company->phones)) {
            $companyText .= "<b>Телефоны для связи</b>\n";

            foreach ($company->phones as $phone)
                $companyText .= "\xF0\x9F\x94\xB8 $phone\n";
        }

        if (!is_null($company->links)) {
            $companyText .= "<b>Интернет ресурсы</b>\n";
            foreach ($company->links as $link)
                $companyText .= "\xF0\x9F\x94\xB8 $link\n";
        }

        $companyText .= "Менеджер: <b>" . ($company->manager ?? 'Не указан') . " </b>\n";
        $companyText .= "Почта: <b>" . ($company->email ?? 'Не указано') . " </b>\n";

        $companyText .= "Наше расположение: <b>" . ($company->address ?? 'Не указано') . " </b>\n\n";

        if (!is_null($company->schedule)) {
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

        $file = InputFile::create(
            file_exists(storage_path("app/public") . "/companies/" . ($company->image ?? 'noimage.jpg')) ?
                storage_path("app/public") . "/companies/" . $company->image :
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
      /*  BotManager::bot()
            ->replyInlineKeyboard("Тестовый магазин", [
                [
                    ["text" => "\xF0\x9F\x8E\xB2Открыть магазин", "web_app" => [
                        "url" => env("APP_URL") . "/test-shop"
                    ]],
                ],

            ]);

        return;*/

        $bot = BotManager::bot()->getSelf();


        Log::info("count image menus".count($bot->imageMenus));
        Log::info(print_r($bot->imageMenus, true));
        if (count($bot->imageMenus) > 1) {

            $media = [];
            foreach ($bot->imageMenus as $image)
            {
                Log::info("media=>".env("APP_URL") . "/images/" . $bot->company->slug . "/" . $image->image);
                $media[] = [
                    "media" => env("APP_URL") . "/images/" . $bot->company->slug . "/" . $image->image,
                    "type" => "photo",
                    "caption" => $image->title
                ];
            }

            BotManager::bot()->replyMediaGroup($media);
        } else if (count($bot->imageMenus) === 1) {


            if (!is_null($bot->imageMenus[0]->image))
                BotManager::bot()->replyPhoto($bot->imageMenus[0]->title,
                    InputFile::create(storage_path("app/public") . "/companies/" . $bot->company->slug . "/" . $bot->imageMenus[0]->image),
                );
            else {
                BotManager::bot()
                    ->replyPhoto("Откройте наше меню!",
                        InputFile::create(storage_path("app/public") . "/companies/" . $bot->company->slug . "/" . $bot->company->image),
                        [
                            [
                                ["text" => "Меню", "url" => $bot->imageMenus[0]->info_link]
                            ]
                        ]);

            }
        }
        BotManager::bot()
            ->sendReplyMenu("Наше меню", "menu_level_3_restaurant_1");


    }

    public function establishments()
    {
        $bot = BotManager::bot()->getSelf();

        $locations = $bot->company->locations;
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

        $tgId = BotManager::bot()->getCurrentChatId();

        \App\Facades\BotManager::bot()
            ->replyPhoto("Заполни эту анкету и получит достук к системе CashBack",
                InputFile::create(public_path()."/images/cashman2.jpg"),
                [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                            "url" => env("APP_URL") . "/restaurant/vip-form/$bot->bot_domain/$tgId"
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

        \App\Facades\BotManager::bot()
            ->replyPhoto("У вас <b>$amount</b> руб.!\n
Для начисления CashBack при оплате за услуги дайте отсканировать данный QR-код сотруднику <b>AR COFFEE</b>",
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
