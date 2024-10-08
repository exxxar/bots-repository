<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Telegram\Bot\FileUpload\InputFile;

class ProfileFormScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $mainScript = BotMenuSlug::query()->updateOrCreate(
            [
                'slug' => "global_profile_form_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Анкета пользователя",
                'comment' => "Система настраиваемой анкеты пользователя",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "mail",
                "description" => "Почта для отправки результата администратору",
                "value" => null
            ],
            [
                "type" => "text",
                "key" => "first_cashback_granted",
                "description" => "Начислить разово сумму кэшбэка после заполнения формы",
                "value" => null
            ],
            [
                "type" => "script",
                "key" => "next_script_id",
                "description" => "Вызов следующего скрипта после этого (id скрипта)",
                "value" => null
            ],
            [
                "type" => "text",
                "key" => "pre_name_text",
                "value" => "Введите ваше имя"
            ],
            [
                "type" => "text",
                "key" => "pre_phone_text",
                "value" => "Введите ваш номер телефона"
            ],
            [
                "type" => "text",
                "key" => "pre_email_text",
                "value" => "Введите ваш адрес электронной почты"
            ],
            [
                "type" => "text",
                "key" => "pre_sex_text",
                "value" => "Выберите ваш пол"
            ],
            [
                "type" => "text",
                "key" => "pre_birthday_text",
                "value" => "Введите вашу дату рождения"
            ],
            [
                "type" => "text",
                "key" => "pre_city_text",
                "value" => "Введите ваш город проживания"
            ],
            [
                "type" => "text",
                "key" => "main_script_text",
                "value" => "Анкета пользователя"
            ],

            [
                "type" => "text",
                "description" => "Текст после отправки формы",
                "key" => "text_after_submit",
                "value" => "Спасибо!"
            ],

            [
                "type" => "text",
                "key" => "profile_btn_caption",
                "value" => "Заполнить анкету"
            ],
            [
                "type" => "boolean",
                "key" => "set_vip",
                "description" => "Устанавливает флаг Вип по окончанию анкеты",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "description" => "Устанавливает флаг Менеджер по окончанию анкеты",
                "key" => "set_manager",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "description" => "Устанавливает флаг Доставщик по окончанию анкеты",
                "key" => "set_deliveryman",
                "value" => true,

            ],


            [
                "type" => "boolean",
                "key" => "need_birthday",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "need_email",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "need_city",
                "value" => true,
            ],
            [
                "type" => "boolean",
                "key" => "need_sex",
                "value" => true,
            ],
            [
                "type" => "boolean",
                "description" => "Скрывает изображение в форме анкеты (лого сверху)",
                "key" => "need_profile_form_image",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "description" => "Скрывает изображение в скрипте",
                "key" => "need_script_image",
                "value" => true,

            ],
            [
                "type" => "image",
                "description" => "Изображение в самой форме",
                "key" => "form_image",
                "value" => null,
            ],
            [
                "type" => "image",
                "description" => "Изображение к скрипту в боте",
                "key" => "script_image",
                "value" => null,
            ],

        ];


        $mainScript->config = $params;
        $mainScript->save();


    }

    /**
     * @throws ValidationException
     */
    public function updateProfileFormData(Request $request)
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            // "birthday" => "required",
            //  "city" => "required",
            //"country" => "required",
            //"address" => "required",
            "sex" => "required",
        ]);

        $customMessage = (Collection::make($request->slug->config)
            ->where("key", "text_after_submit")
            ->first())["value"] ?? null;

        BusinessLogic::administrative()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->vipStore($request->all(), $customMessage);


        if (!is_null($request->slug ?? null)) {

            $nextScriptId = (Collection::make($request->slug->config)
                ->where("key", "next_script_id")
                ->first())["value"] ?? null;

            if (!is_null($nextScriptId)) {
                $isRun = BotManager::bot()
                    ->runPage($nextScriptId, $request->bot, $request->botUser);

                if (!$isRun)
                    BotManager::bot()
                        ->runSlug($nextScriptId, $request->bot, $request->botUser);
            }

        }

        $mail = (Collection::make($request->slug->config)
            ->where("key", "mail")
            ->first())["value"] ?? null;

        if (!is_null($mail)) {
            $botUser = $request->botUser;

            $bot = $request->bot;

            $data = [
                "telegram_chat_id" => $botUser->telegram_chat_id ?? 'не указан',
                "fio_from_telegram" => $botUser->fio_from_telegram ?? 'не указан',
                'name' => $botUser->name ?? 'не указан',
                'username' => $botUser->username ?? 'не указан',
                'phone' => $botUser->phone ?? 'не указан',
                'email' => $botUser->email ?? 'не указан',
                'birthday' => $botUser->birthday ?? 'не указан',
                'age' => $botUser->age ?? 'не указан',
                'city' => $botUser->city ?? 'не указан',
                'country' => $botUser->country ?? 'не указан',
                'address' => $botUser->address ?? 'не указан',
                'sex' => $botUser->sex == 1 ? "Мужской" : "Женский",

            ];

            Mail::send('emails.profile', $data, function ($message) use ($bot, $mail, $botUser) {
                $message->to($mail, $bot->bot_domain)->subject("Анкет от пользователя " . ($botUser->telegram_chat_id ?? '-'));
                $message->from(env("APP_EMAIL"), 'YourCashman:' . $bot->bot_domain);
            });
        }

        BusinessLogic::bitrix()
            ->setBotUser($request->botUser ?? null)
            ->setBot($request->bot ?? null)
            ->addLead("Заполнение профиля");

        return response()->noContent();
    }

    public function loadProfileFormData(Request $request)
    {
        $slug = $request->slug;

        return response()->json(
            [
                'pre_name_text' => (Collection::make($slug->config)
                        ->where("key", "pre_name_text")
                        ->first())["value"] ?? 'Укажите ваш возраст',
                'form_image' => (Collection::make($slug->config)
                        ->where("key", "form_image")
                        ->first())["value"] ?? null,
                'pre_birthday_text' => (Collection::make($slug->config)
                        ->where("key", "pre_birthday_text")
                        ->first())["value"] ?? 'Укажите вашу дату рождения',
                'text_after_submit' => (Collection::make($slug->config)
                        ->where("key", "text_after_submit")
                        ->first())["value"] ?? 'Спасибо!',
                'need_birthday' => (Collection::make($slug->config)
                        ->where("key", "need_birthday")
                        ->first())["value"] ?? true,
                'pre_email_text' => (Collection::make($slug->config)
                        ->where("key", "pre_email_text")
                        ->first())["value"] ?? 'Укажите вашу электронную почту',
                'need_email' => (Collection::make($slug->config)
                        ->where("key", "need_email")
                        ->first())["value"] ?? true,
                'need_profile_form_image' => (Collection::make($slug->config)
                        ->where("key", "need_profile_form_image")
                        ->first())["value"] ?? true,

                'pre_city_text' => (Collection::make($slug->config)
                        ->where("key", "pre_city_text")
                        ->first())["value"] ?? 'Укажите ваш город',
                'need_city' => (Collection::make($slug->config)
                        ->where("key", "need_city")
                        ->first())["value"] ?? true,
                'pre_sex_text' => (Collection::make($slug->config)
                        ->where("key", "pre_sex_text")
                        ->first())["value"] ?? 'Укажите ваш пол',
                'need_sex' => (Collection::make($slug->config)
                        ->where("key", "need_sex")
                        ->first())["value"] ?? true,
                'pre_phone_text' => (Collection::make($slug->config)
                        ->where("key", "pre_phone_text")
                        ->first())["value"] ?? 'Укажите ваш номер телефона',
                'need_phone' => (Collection::make($slug->config)
                        ->where("key", "need_phone")
                        ->first())["value"] ?? true,

            ]
        );
    }

    public function profileFormMain(...$config)
    {

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $image = (Collection::make($config[1])
            ->where("key", "script_image")
            ->first())["value"] ?? null;

        $needScriptImage = (Collection::make($config[1])
            ->where("key", "need_script_image")
            ->first())["value"] ?? false;


        $mainScriptText = (Collection::make($config[1])
            ->where("key", "main_script_text")
            ->first())["value"] ?? 'Анкета';

        $btnText = (Collection::make($config[1])
            ->where("key", "profile_btn_caption")
            ->first())["value"] ?? 'Заполнить анкету';

        $bot = BotManager::bot()->getSelf();


        $keyboard = [
            [
                ["text" => "$btnText", "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId&hide_menu#/s/new-profile-form"
                ]],
            ],

        ];

        if ($needScriptImage)
            \App\Facades\BotManager::bot()
                ->replyPhoto($mainScriptText,
                    InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                    $keyboard);
        else
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard($mainScriptText, $keyboard);

    }
}
