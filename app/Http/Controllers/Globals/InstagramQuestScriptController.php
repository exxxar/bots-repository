<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class InstagramQuestScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_instagram_quest",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Insta-квест",
                'comment' => "Модуль создания задания для инстаграм",
            ]);



        $params =  [
            [
                "type" => "text",
                "key" => "max_attempts",
                "value" => 2,

            ],
            [
                "type" => "channel",
                "key" => "callback_channel_id",
                "value" => $bot->order_channel ??  env("BASE_ADMIN_CHANNEL"),

            ],
            [
                "type" => "text",
                "key" => "rules_text",
                "value" => "Всё гениальное просто - делай фото по заданию и загружай их!",

            ],
            [
                "type" => "text",
                "key" => "main_text",
                "value" => "Принимай участие в наших квестах и получай ценные призы!",

            ],
            [
                "type" => "text",
                "key" => "result_message",
                "value" => "%s, вы приняли участие в квесте и скоро получите награду. Наш менеджер свяжется с вами в ближайшее время!",

            ],
            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "К заданию",

            ],

        ];


            $model->config = $params;
            $model->save();


    }

    public function instagramQuestCallback(Request $request)
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
        ]);

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $imageName = null;
        $companySlug = $bot->company->slug;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;


            $file->storeAs("/public/companies/$companySlug/$imageName");

        }

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            env("BASE_ADMIN_CHANNEL");

        $winMessage = (Collection::make($slug->config)
            ->where("key", "result_message")
            ->first())["value"] ?? "%s, вы приняли участие в квесте и скоро получите награду. Наш менеджер свяжется с вами в ближайшее время!";

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0,
                    'bot_user_id'=>$botUser->id,
                ]);

        $action->current_attempts++;
        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();

        $winnerName = $request->name ?? 'Имя не указано';
        $winnerPhone = $request->phone ?? 'Телефон не указан';

        $botUser->name = $botUser->name ?? $winnerName;
        $botUser->phone = $botUser->phone ?? $winnerPhone;
        $botUser->save();

        $username = $botUser->username ?? null;

        $tmp[] = (object)[
            "name" => $winnerName,
            "phone" => $winnerPhone,
            "answered_at" => null,
            "answered_by" => null,
        ];

        $action->data = $tmp;

        $action->save();

        $path = storage_path("app/public") . "/companies/$companySlug/" . ($imageName ?? 'noimage.jpg');
        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/cashman.jpg"
        );

        $thread = $bot->topics["actions"] ?? null;

        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($botUser
                ->telegram_chat_id,
                sprintf($winMessage, $winnerName))
            ->sendPhoto($callbackChannel,
                "Участника $winnerPhone ($winnerName " . ($username ? "@$username" : 'Домен не указан') . ") принял участие в InstagramQuest - свяжитесь с ним для дальнейших указаний",
                $file,
                $thread
            );

        return response()->noContent();
    }

    public function loadData(Request $request)
    {

        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;


        $rules = Collection::make($slug->config ?? [])
            ->where("key", "rules_text")
            ->first() ?? null;

        return response()->json(
            [
                'rules' => $rules["value"] ?? null,
            ]

        );
    }

    public function instagramQuestPrepare(Request $request)
    {
        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0,
                    'bot_user_id'=>$botUser->id,
                ]);

        return response()->json([
            "action" => new ActionStatusResource($action)
        ]);
    }

    public function instagramQuest(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Участвуй в квесте и получай призы";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Перейти к выполнению задания";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard($mainText,
               // InputFile::create(public_path() . "/images/cashman-quest.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/$bot->bot_domain?slug=$slugId#/instagram-quest"
                        ]],
                    ],

                ]);
    }
}
