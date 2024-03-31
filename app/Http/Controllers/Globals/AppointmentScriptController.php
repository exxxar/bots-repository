<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class AppointmentScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_appointment_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Записаться на прием",
                'comment' => "Сервис записей на прием",
            ]);

        $params = [];

        $model->config = $params;
        $model->save();


    }

    public function appointmentService(...$config)
    {
        $bot = BotManager::bot()->getSelf();
        BotManager::bot()
            ->replyInlineKeyboard("Запишись на прием к специалисту",
                [

                    [
                        [
                            "text" => "\xF0\x9F\x8D\x80Открыть список сервисов",
                            "web_app" => [
                                "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=route#/appointment-events"
                            ]
                        ],
                    ],

                ]
            );

    }
}
