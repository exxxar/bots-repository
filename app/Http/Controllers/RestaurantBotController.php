<?php

namespace App\Http\Controllers;

use App\Classes\QRCodeHandler;
use App\Facades\BotManager;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class RestaurantBotController extends Controller
{

    public function startWithParam(...$data)
    {
        $botUser = BotManager::bot()->currentBotUser();

        if (!is_null($data[2])) {
            $pattern = "/([0-9]{3})([0-9]+)/";

            $string = base64_decode($data[2]);

            preg_match_all($pattern, $string, $matches);

            $code = $matches[1][0] ?? null;
            $request_user_id = $matches[2][0] ?? null;

            //$qrCode = new QRCodeHandler($code, $request_user_id);

            if ($botUser->is_admin) {
                $bot_domain = BotManager::bot()->getSelf()->bot_domain;
                BotManager::bot()->replyInlineKeyboard(
                    "Административное меню",
                    [
                        [
                            ["text" => "\xF0\x9F\x8E\xB0Перейти в админку бота",
                                "web_app" => [
                                    "url" => env("APP_URL") . "/admin/$bot_domain/$request_user_id"
                                ]
                            ],
                        ]
                    ]
                );
            }

            $userBotUser = BotUser::query()
                ->where("telegram_chat_id", $request_user_id)
                ->where("bot_id", BotManager::bot()->getSelf()->id)
                ->first();

            if (is_null($userBotUser)) {
                BotManager::bot()->reply("Данный код не корректный!");
                return;
            }

            $userBotUser->user_in_location = true;
            $userBotUser->save();

            BotManager::bot()->reply("QR-код успешно обработан!");
        }


        BotManager::bot()
            ->sendReplyMenu("Главное меню",
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

        \App\Facades\BotManager::bot()
            ->replyPhoto("Перешли эту ссылку друзьям:\n<a href=\"$qr\">$qr</a>\n<span class=\"tg-spoiler\">И получи бонусные баллы <strong>CashBack</strong></span>",
                InputFile::create(
                    storage_path("app\\public") . "\\companies\\" . $botDomain . "\\" . $bot->image
                ));


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


        if (is_null($company->image))
            BotManager::bot()
                ->replyInlineKeyboard($companyText, $keyboard);
        else
            BotManager::bot()
                ->replyPhoto($companyText,
                    InputFile::create(storage_path("app\\public") . "\\companies\\" . $company->slug . "\\" . $company->image),
                    $keyboard
                );

      /*  \App\Facades\BotManager::bot()
            ->sendReplyMenu("Наше расположение", "menu_level_2_restaurant_4");*/
    }

    public function locationInfo(...$data)
    {

        $location = Location::query()
            ->with(["company"])
            ->where("id", ($data[2] ?? null))
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
                foreach ($location->images as $image)
                    $media[] = [
                        "media" => InputFile::create(storage_path("app\\public") . "\\companies\\" . $location->company->slug . "\\" . $image->image),
                        "type" => "photo",
                    ];
                BotManager::bot()->replyMediaGroup($media);
            } else if (count($location->images) === 1) {
                BotManager::bot()->replyPhoto("Фотографии нашего заведения",
                    InputFile::create(storage_path("app\\public") . "\\companies\\" . $location->company->slug . "\\" . $location->images[0]),

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


    public function menu()
    {

        $bot = BotManager::bot()->getSelf();


        if (count($bot->imageMenus) > 1) {
            $media = [];
            foreach ($bot->imageMenus as $image)
                $media[] = [
                    "media" => InputFile::create(storage_path("app\\public") . "\\companies\\" . $bot->company->slug . "\\" . $image->images),
                    "type" => "photo",
                    "caption" => $image->title
                ];
            BotManager::bot()->replyMediaGroup($media);
        } else if (count($bot->imageMenus) === 1) {
            BotManager::bot()->replyPhoto($bot->imageMenus[0]->title,

                InputFile::create(storage_path("app\\public") . "\\companies\\" . $bot->company->slug . "\\" . $bot->imageMenus[0]->image),

            );

        }
        BotManager::bot()
            ->sendReplyMenu("Наше меню", "menu_level_3_restaurant_1");


    }

    public function establishments()
    {
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("Заведения", "cashback_buttons_1");
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
                InputFile::create("https://phonoteka.org/uploads/posts/2022-09/1663726294_49-phonoteka-org-p-oboi-vip-persona-vkontakte-58.jpg"),
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
            ->sendReplyMenu("myBudget", "menu_level_3_restaurant_2");
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
            ->sendInlineMenu("friendsNetwork", "cashback_buttons_1");
    }

    public function bookTable()
    {
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("Укажите какой именно столик вы хотите забронировать", "booking_table_1");
    }

    public function charges()
    {
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("charges", "cashback_buttons_1");
    }

    public function writeOffs()
    {
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("writeOffs", "cashback_buttons_1");
    }

    public function myFriends()
    {
        \App\Facades\BotManager::bot()
            ->sendInlineMenu("myFriends", "cashback_buttons_1");
    }


}
