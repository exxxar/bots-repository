<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
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
                "key" => "main_script_text",
                "value" => "Анкета пользователя"
            ],
            [
                "type" => "text",
                "key" => "profile_btn_caption",
                "value" => "Заполнить анкету"
            ],
            [
                "type" => "boolean",
                "key" => "set_vip",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "set_manager",
                "value" => true,

            ],
            [
                "type" => "boolean",
                "key" => "set_deliveryman",
                "value" => true,

            ],

            [
                "type" => "boolean",
                "key" => "need_profile_form_image",
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
                "key" => "need_age",
                "value" => true,
            ],
            [
                "type" => "form_image",
                "key" => "image",
                "value" => null,
            ],
            [
                "type" => "script_image",
                "key" => "image",
                "value" => null,
            ],

        ];

        if (count($mainScript->config ?? []) != count($params)) {
            $mainScript->config = $params;
            $mainScript->save();
        }


    }

    public function updateProfileFormData(Request $request){

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
                'pre_age_text' => (Collection::make($slug->config)
                        ->where("key", "pre_age_text")
                        ->first())["value"] ?? 'Укажите ваш возраст',
                'need_age' => (Collection::make($slug->config)
                        ->where("key", "need_age")
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

        $mainScriptText = (Collection::make($config[1])
            ->where("key", "main_script_text")
            ->first())["value"] ?? 'Анкета';

        $btnText = (Collection::make($config[1])
            ->where("key", "profile_btn_caption")
            ->first())["value"] ?? 'Заполнить анкету';

        $bot = BotManager::bot()->getSelf();


        \App\Facades\BotManager::bot()
            ->replyPhoto($mainScriptText,
                InputFile::create($image ?? public_path() . "/images/cashman2.jpg"),
                [
                    [
                        ["text" => "$btnText", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/profile-form"
                        ]],
                    ],

                ]);

    }
}
