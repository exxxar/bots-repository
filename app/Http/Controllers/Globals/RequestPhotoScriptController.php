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
use Spatie\Image\Image;
use Telegram\Bot\FileUpload\InputFile;

class RequestPhotoScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_request_photo",
                'is_global' => true,
                'parent_slug_id' => null,
                'bot_id' => null,
            ],
            [
                'command' => ".*Скрипт запроса фотографии",
                'comment' => "Модуль запроса фото и комментария",
            ]);


        $params = [

            [
                "type" => "channel",
                "key" => "callback_channel_id",
                "value" => $bot->order_channel ?? env("BASE_ADMIN_CHANNEL"),

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

    public function RequestPhotoCallback(Request $request)
    {
        $bot = $request->bot;
        $botUser = $request->botUser;
        $slug = $request->slug;

        $imageName = null;
        $companySlug = $bot->company->slug;

        $comment = $request->comment ?? '-';

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            env("BASE_ADMIN_CHANNEL");

        $thread = $bot->topics["actions"] ?? null;

        $winMessage = (Collection::make($slug->config)
            ->where("key", "result_message")
            ->first())["value"] ?? "%s, вы приняли участие в квесте и скоро получите награду. Наш менеджер свяжется с вами в ближайшее время!";

        $winnerName = $botUser->name ?? $botUser->fio_from_telegram ?? 'Имя не указано';
        $winnerPhone = $botUser->phone ?? 'Телефон не указан';

        $username = $botUser->username ?? $botUser->telegra_chat_id ?? null;

        $uploadedPhotos = $request->hasFile('photos') ? $request->file('photos') : null;

        $adminMessage = "Участник $winnerPhone ($winnerName " . ($username ? "@$username" : 'Домен не указан') . ") отправил фото с комментарием $comment  - свяжитесь с ним для дальнейших указаний";
        if (!is_null($uploadedPhotos)) {

            if (count($uploadedPhotos) > 1) {
                $media = [];
                foreach ($uploadedPhotos as $key => $photo) {
                    $ext = $photo->getClientOriginalExtension();

                    $imageName = Str::uuid() . "." . $ext;

                    $divider = env("APP_DEBUG") ? "\\" : "/";

                    $path = public_path('uploads') . $divider . "$imageName";

                    $photo->move(public_path('uploads'), $imageName);

                    Image::load($path)
                        ->width(800) // Установить ширину
                        ->optimize() // Оптимизация для снижения размера файла
                        ->save();

                    $media[] = [
                        "media" => env("APP_URL") . "/uploads/" . $imageName,
                        "type" => "photo",
                        "caption" => "$imageName"
                    ];

                }

                BotMethods::bot()
                    ->whereBot($bot)
                    ->sendMediaGroup($callbackChannel, json_encode($media), $thread)
                    ->sendMessage($callbackChannel, $adminMessage, $thread);
            } else {
                $ext = $uploadedPhotos[0]->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $divider = env("APP_DEBUG") ? "\\" : "/";

                $path = public_path('uploads') . $divider . "$imageName";

                $uploadedPhotos[0]->move(public_path('uploads'), $imageName);

                Image::load($path)
                    ->width(800) // Установить ширину
                    ->optimize() // Оптимизация для снижения размера файла
                    ->save();

                BotMethods::bot()
                    ->whereBot($bot)
                    ->sendPhoto(
                        $callbackChannel,
                        $adminMessage,
                        InputFile::create(env("APP_URL") . "/uploads/" . $imageName),
                        [],
                        $thread
                    );
            }

        } else {
            BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage(
                    $callbackChannel,
                    $adminMessage,
                    $thread
                );
        }


        $path = storage_path("app/public") . "/companies/$companySlug/" . ($imageName ?? 'noimage.jpg');

        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/cashman.jpg"
        );


        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($botUser
                ->telegram_chat_id,
                sprintf($winMessage, $winnerName));

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


    public function requestPhoto(...$config)
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
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId&hide_menu#/s/request-photo"
                        ]],
                    ],

                ]);
    }
}
