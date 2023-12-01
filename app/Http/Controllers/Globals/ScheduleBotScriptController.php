<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class ScheduleBotScriptController extends SlugController
{
    public function config(Bot $bot)
    {
        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_schedule_bot_main",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*График работы",
                'comment' => "Автоматизированная информация о графике работы",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Информация о часах работы магазина",

            ],

            [
                "type" => "image",
                "key" => "main_image",
                "value" => null,

            ],

            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "Открыть график",

            ],
            [
                "type" => "text",
                "key" => "opened_comment",
                "value" => "Мы работаем! Ураа",

            ],
            [
                "type" => "text",
                "key" => "closed_comment",
                "value" => "Мы закрыты! Эх",

            ],
            [
                "type" => "json",
                "key" => "schedule",
                "value" => [
                    (object)[
                        "day" => "Понедельник",
                        "start_at" => "06:00",
                        "end_at" => "18:00",
                        "closed" => false,

                    ],
                    (object)[
                        "day" => "Вторник",
                        "start_at" => "06:00",
                        "end_at" => "18:00",
                        "closed" => false,

                    ],
                    (object)[
                        "day" => "Среда",
                        "start_at" => "06:00",
                        "end_at" => "18:00",
                        "closed" => false,

                    ],
                    (object)[
                        "day" => "Четверг",
                        "start_at" => "06:00",
                        "end_at" => "18:00",
                        "closed" => false,

                    ], (object)[
                        "day" => "Пятница",
                        "start_at" => "06:00",
                        "end_at" => "18:00",
                        "closed" => false,

                    ],
                    (object)[
                        "day" => "Суббота",
                        "start_at" => "06:00",
                        "end_at" => "15:00",
                        "closed" => false,

                    ],
                    (object)[
                        "day" => "Воскресенье",
                        "start_at" => "06:00",
                        "end_at" => "18:00",
                        "closed" => true,

                    ]
                ],

            ],


        ];

        if (count($model->config ?? []) != count($params)) {
            $model->config = $params;
            $model->save();
        }

    }

    public function loadData(Request $request)
    {

        $slug = $request->slug;

        $schedule = Collection::make($slug->config ?? [])
            ->where("key", "schedule")
            ->first()["value"] ?? null;

        $openedComment = Collection::make($slug->config ?? [])
            ->where("key", "opened_comment")
            ->first()["value"] ?? 'Открыты';

        $closedComment = Collection::make($slug->config ?? [])
            ->where("key", "closed_comment")
            ->first()["value"] ?? 'Закрыты';


        $schedule = Collection::make($slug->config ?? [])
            ->where("key", "schedule")
            ->first()["value"] ?? null;

        $day = Carbon::now("+3:00")->dayOfWeek - 1;

        $hour = Carbon::now("+3:00")->hour;

        if (!($schedule[$day]["closed"] ?? false)) {
            $startHour = explode(":", $schedule[$day]["start_at"])[0] ?? 0;
            $endHour = explode(":", $schedule[$day]["end_at"])[0] ?? 23;

            return response()->json(
                [
                    'schedule' => $schedule,
                    'current_day' => $day,
                    'opened_comment' => $openedComment,
                    'closed_comment' => $closedComment,
                    'is_work' => $hour >= (int)$startHour && $hour <= (int)$endHour,
                ]

            );
        }else {
            return response()->json(
                [
                    'schedule' => $schedule,
                    'current_day' => $day,
                    'opened_comment' => $openedComment,
                    'closed_comment' => $closedComment,
                    'is_work' => false,
                ]

            );
        }

    }

    public function scheduleBotMain(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Расписание";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Открыть";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $mainImage = (Collection::make($config[1])
            ->where("key", "main_image")
            ->first())["value"] ?? null;

        $keyboard = [
            [
                ["text" => "$btnText", "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/schedule-main"
                ]],
            ],

        ];

        if (is_null($mainImage))
            \App\Facades\BotManager::bot()
                ->replyInlineKeyboard("$mainText", $keyboard);
        else
            \App\Facades\BotManager::bot()
                ->replyPhoto("$mainText", $mainImage, $keyboard);

    }
}
