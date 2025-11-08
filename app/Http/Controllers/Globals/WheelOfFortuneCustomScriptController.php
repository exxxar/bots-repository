<?php

namespace App\Http\Controllers\Globals;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\BotMenuSlugResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use HttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use ReflectionClass;
use stdClass;
use Telegram\Bot\FileUpload\InputFile;

class WheelOfFortuneCustomScriptController extends SlugController
{
    public function config(Bot $bot)
    {

        $model = BotMenuSlug::query()->updateOrCreate(
            [
                "slug" => "global_wheel_of_fortune_custom",
                'parent_slug_id' => null,
                'bot_id' => null,
                'is_global' => true,
            ],
            [
                'command' => ".*Колесо фортуны вариант 2",
                'comment' => "Игровой модуль",
            ]);

        $params = [
            [
                "type" => "text",
                "key" => "next_win_page_id",
                "description" => "Вызов следующей страницы при победе (id страницы)",
                "value" => null
            ],

            [
                "type" => "image",
                "key" => "main_image",
                "value" => null,

            ],
            [
                "type" => "text",
                "key" => "max_attempts",
                "value" => 2,

            ],
            [
                "type" => "script",
                "key" => "profile_id",
                "value" => null,

            ],
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
                "key" => "win_message",
                "value" => "%s, вы приняли участие в квесте и скоро получите награду. Наш менеджер свяжется с вами в ближайшее время!",

            ],
            [
                "type" => "text",
                "key" => "callback_message",
                "value" => "Спасибо за ваше участие! Вы выиграли приз!
Когда будете готовы сделать заказ, позвоните по номеру: +7 (999) 418-28-84 и при заказе, обязательно, уточните что вы выиграли в «Колесо Фортуны»",

            ],
            [
                "type" => "text",
                "key" => "btn_text",
                "value" => "К заданию",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№1",
                "bg_color" => null,

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№2",
                "bg_color" => null,

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№3",

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№4",
                "bg_color" => null,

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№5",
                "bg_color" => null,

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№6",
                "bg_color" => null,

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№7",
                "bg_color" => null,

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№8",
                "bg_color" => null,

            ],
            [
                "type" => "text",
                "key" => "wheel_text",
                "value" => "№9",
                "bg_color" => null,

            ],
        ];


        $model->config = $params;
        $model->save();


    }

    /**
     * @throws HttpException
     */
    public function loadPrizesVariants(Request $request)
    {
        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;

        $slugId = $request->slug_id ?? null;

        $slug = BotMenuSlug::query()
            ->find($slugId);

        return $this->extractedPreparedPrizes($bot, $botUser, $slug);


    }

    public function loadScriptVariants(Request $request)
    {

        $bot = $request->bot ?? null;

        //SELECT * FROM `bot_menu_slugs` WHERE (`slug`="global_friends_main" AND `bot_id`=21) OR `parent_slug_id`=1531 OR `parent_slug_id`=1574
        $globalScripts = BotMenuSlug::query()
            ->where("slug", "global_wheel_of_fortune_custom")
            //->where("bot_id",$bot->id)
            ->get();

        $ids = $globalScripts->pluck("id");

        $parentScripts = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->whereIn("parent_slug_id", $ids)
            ->get();

        $baseScripts = BotMenuSlug::query()
            ->where("slug", "global_wheel_of_fortune_custom")
            ->where("bot_id", $bot->id)
            ->get();

        return [...$baseScripts->toArray(), ...$parentScripts->toArray()];
    }

    /**
     * @throws HttpException
     */
    public function formWheelOfFortuneCallback(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            //   "name" => "required",
            //   "phone" => "required",
            "win" => "required"
        ]);

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;
        $slug = $request->slug ?? null;

        if (is_null($bot) || is_null($botUser) || is_null($slug))
            throw new HttpException("Не заданы необходимые параметры функции", 400);


        $maxAttempts = (Collection::make($slug->config ?? [])
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $callbackChannel = (Collection::make($slug->config ?? [])
            ->where("key", "callback_channel_id")
            ->first())["value"] ??
            $bot->order_channel ??
            env("BASE_ADMIN_CHANNEL");

        $winMessage = (Collection::make($slug->config)
            ->where("key", "win_message")
            ->first())["value"] ?? "Спасибо за участие!";

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            throw new HttpException("Вы еще не начали розыгрыш!", 400);

        $action->current_attempts++;
        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();


        $winNumber = $request->win ?? null;



        $winnerName = $request->name ?? $botUser->name ?? 'Имя не указано';
        $winnerPhone = $request->phone ?? $botUser->phone ?? 'Телефон не указан';
        $winnerDescription = $request->description ?? 'Без описания';
        $winPrizeType = $request->type ?? "text";
        $winPrizeEffectValue = $request->effect_value ?? 0;
        $winPrizeEffectProduct = $request->effect_product ?? null;
        $winMarketPlaceId = $request->marketplace_id ?? null;
        $reviewRating = $request->review_rating ?? null;
        $reviewText = $request->review_text ?? '-';


        $username = $botUser->username ?? null;

        $tmp = $action->data ?? [];

        dd([
            "type"=>$winPrizeType,
            "value"=>$winPrizeEffectValue,
        ]);

        $tmp[] = (object)[
            "name" => $winnerName,
            "win" => $winNumber,
            "description" => $winnerDescription,
            "type" => $winPrizeType,
            'marketplace_id' => $winMarketPlaceId,
            "effect_value" => $winPrizeEffectValue,
            "effect_product" => $winPrizeEffectProduct,
            "phone" => $winnerPhone,
            "played_at" => Carbon::now(),
            "answered_at" => null,
            "answered_by" => null,
        ];

        $action->data = $tmp;

        $link = "https://t.me/$bot->bot_domain?start=" . base64_encode("003$botUser->telegram_chat_id");

        //$action->max_attempts = $maxAttempts;
        $action->save();

        $thread = $bot->topics["actions"] ?? null;

        $vowels = ["(", ")", "-"];
        $filteredPhone = str_replace($vowels, "", $winnerPhone);

        $winMessage = str_replace(["{{name}}"], $winnerName ?? 'имя не указано', $winMessage);
        $winMessage = str_replace(["%s"], $winnerName ?? 'имя не указано', $winMessage);
        $winMessage = str_replace(["{{phone}}"], $winnerPhone ?? 'телефон не указан', $winMessage);
        $winMessage = str_replace(["{{prize}}"], $winnerDescription ?? 'описание приза не указано', $winMessage);
        $winMessage = str_replace(["{{username}}"], "@" . ($username ?? 'имя не указано'), $winMessage);

        $messageToAdmin = "🎡<b>Колесо фортуны</b>\n\n" .
            "📋<b>Информация об участнике:</b>\n" .
            "Номер телефона: " . ($filteredPhone ?? 'не указан') . "\n" .
            "Введенное имя пользователя: " . ($winnerName ?? 'не указано') . "\n" .
            "Имя из телеграм: " . ($botUser->fio_from_telegram ?? 'не указано') . "\n" .
            "Телеграм id: " . ($botUser->telegram_chat_id ?? 'не указано') . "\n" .
            "Домен: " . ($username ? "@$username" : 'Домен не указан') . "\n";

        if (!is_null($reviewRating)) {
            $messageToAdmin .= "Пользователь поставил рейтинг: $reviewRating баллов ($reviewText)\n";
        }

        if (!is_null($winMarketPlaceId)) {
            $marketplace = [
                "ozon" => "Озон",
                "wb" => "Wildberries",
                "yandex" => "Яндекс.Маркет"
            ][$winMarketPlaceId] ?? 'Не указан';

            $messageToAdmin .= "Пользователь выбрал маркетплейс: $marketplace\n";
        }

        if (!is_null($botUser->email ?? null))
            $messageToAdmin .= "Почта: " . ($botUser->email ?? 'не указано') . "\n";
        if (!is_null($botUser->city ?? null))
            $messageToAdmin .= "Город: " . ($botUser->city ?? 'не указано') . "\n";
        if (!is_null($botUser->birthday ?? null))
            $messageToAdmin .= "День рождения: " . ($botUser->birthday ?? 'не указано') . "\n";
        if (!is_null($botUser->sex ?? null))
            $messageToAdmin .= "Пол: " . ($botUser->sex ?? 'не указано') . "\n";

        $messageToAdmin .= "\n<b>Информация о призе:</b>\n" .
            "Описание приза: " . ($winnerDescription ?? 'не указано');

        $tmpUserLink = "\n<a href='tg://user?id=$botUser->telegram_chat_id'>Перейти к чату с пользователем</a>\n";

        $messageToAdmin .= $tmpUserLink;

        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendMessage($botUser
                ->telegram_chat_id, $winMessage);


        if (!is_null($request->get("file_1") ?? null)) {
            $uploadedFile = $request->get("file_1");
            $ext = $uploadedFile->getClientOriginalExtension();
            $imageName = Str::uuid() . "." . $ext;
            //$uploadedFile->storeAs("$imageName");
            BotMethods::bot()
                ->whereDomain($bot->bot_domain)
                ->sendPhoto($callbackChannel, "Фото от пользователя 1", InputFile::createFromContents($uploadedFile, $imageName));
            sleep(1);
        }

        if (!is_null($request->get("file_2") ?? null)) {
            $uploadedFile = $request->get("file_2");
            $ext = $uploadedFile->getClientOriginalExtension();
            $imageName = Str::uuid() . "." . $ext;
            //$uploadedFile->storeAs("$imageName");
            BotMethods::bot()
                ->whereDomain($bot->bot_domain)
                ->sendPhoto($callbackChannel, "Фото от пользователя 2", InputFile::createFromContents($uploadedFile, $imageName));
            sleep(1);
        }

        sleep(1);
        BotMethods::bot()
            ->whereDomain($bot->bot_domain)
            ->sendInlineKeyboard($callbackChannel,
                $messageToAdmin, [
                    [
                        ["text" => "Написать пользователю ответ", "url" => $link]
                    ]
                ], $thread);

        if (!is_null($request->slug ?? null)) {
            $nextWinPageId = (Collection::make($request->slug->config)
                ->where("key", "next_win_page_id")
                ->first())["value"] ?? null;

            if (!is_null($nextWinPageId)) {
                $isRun = BotManager::bot()
                    ->runPage($nextWinPageId, $bot, $botUser);

                if (!$isRun)
                    BotManager::bot()
                        ->runSlug($nextWinPageId, $bot, $botUser);
            }


        }

        BusinessLogic::bitrix()
            ->setBotUser($botUser)
            ->setBot($bot)
            ->addLead("Участие в колесе фортуны");


        return response()->noContent();
    }

    /**
     * @throws HttpException
     */
    public function formWheelOfFortunePrepare(Request $request)
    {

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;
        $slug = $request->slug ?? null;

        return $this->extractedPreparedPrizes($bot, $botUser, $slug);
    }

    /**
     * @throws HttpException
     */
    public function loadData(Request $request)
    {

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;
        $slug = $request->slug ?? null;

        if (is_null($bot) || is_null($botUser) || is_null($slug))
            throw new HttpException("Не заданы необходимые параметры функции", 400);


        $dictionary = [
            "rules_text" => "Правила колеса фортуны",
            "callback_message" => "Спасибо за участие в розыгрыше, {{name}}!",
            "main_text" => "Колесо фортуны!",
            "btn_text" => "Поехали!",
            "max_attempts" => 1,
            "can_play" => true,
            "wheels" => Collection::make($slug->config ?? [])
                ->where("key", "wheel_text")
                ->skip(0)
                ->take(20)
                ->values()
                ->toArray(),
            "win_message" => "{{name}}, вы приняли участие в розыгрыше и выиграли приз {{prize}}. Наш менеджер свяжется с вами в ближайшее время!",
        ];


        $configCollection = Collection::make($slug->config)
            ->where("key", "!=", "wheel_text")
            ->all();


        if (!is_null($configCollection ?? null)) {
            $tmp = [];

            foreach ($configCollection ?? [] as $item) {
                $item = (object)$item;
                $tmp[$item->key] = is_null($item->value ?? null) ? ($dictionary[$item->key] ?? "") : $item->value;
            }


            foreach ($dictionary as $key => $item) {

                if (!is_null($key ?? null))
                    if (!array_key_exists($key, $tmp))
                        $tmp["$key"] = $item;


            }

            return $tmp;
        }

        return $dictionary;

    }

    /**
     * @throws HttpException
     */
    public function storeParams(Request $request)
    {

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;
        $slug = $request->slug ?? null;

        if (is_null($bot) || is_null($slug))
            throw new HttpException("Не все параметры функции заданы!", 404);

        $slug = BotMenuSlug::query()->find($slug->id);


        if (is_null($slug))
            throw new HttpException(404, "Команда не найдена!");

        $data = $request->all();

        $config = $bot->config ?? [];
        $config["selected_script_id"] = (($data["use_in_shop"] ?? false) == "true") ? $slug->id : null;
        $bot->config = $config;
        $bot->save();

        $data["can_play"] = (($data["can_play"] ?? false) == "true");
        $data["rules_text"] = $data["rules_text"] ?? "Правила игры";
        $data["main_text"] = $data["main_text"] ?? "Начни игру, испытай удачи, выиграй приз!";
        $data["btn_text"] = $data["btn_text"] ?? "Поехали!";
        $data["callback_message"] = $data["callback_message"] ?? "Поехали!";
        $data["win_message"] = $data["win_message"] ?? "Поехали!";
        $data["max_attempts"] = $data["max_attempts"] ?? 0;
        $data["before_script"] = $data["before_script"] ?? null;
        $data["after_script"] = $data["after_script"] ?? null;
        $wheels = json_decode($data["wheels"] ?? '[]');
        unset($data["wheels"]);
        unset($data["use_in_shop"]);


        $config = Collection::make($slug->config ?? []);

        $tmp = $slug->config ?? [];

        foreach ($tmp as $key => $item) {
            if ($item["key"] == "wheel_text" || $item["key"] == "wheels") {
                unset($tmp[$key]);
                continue;
            }

            $configItem = $config->where("key", $item["key"])->first() ?? null;
            $configItem["value"] = $data[$item["key"]] ?? null;
            $configItem["bg_color"] = isset($item["bg_color"]) ? $data[$item["bg_color"]] ?? null : null;
            $configItem["smile"] = isset($item["smile"]) ? $data[$item["smile"]] ?? null : null;
            $tmp[$key] = $configItem;


        }

        foreach (array_keys($data) as $key) {

            $configItem = $config->where("key", $key)->first() ?? null;

            if (is_null($configItem)) {
                $tmp[] = [
                    "key" => $key,
                    "type" => "json",
                    "value" => $data[$key]
                ];
            }

        }

        if (count($wheels) > 0)
            foreach ($wheels as $wheel) {
                $wheel = (object)$wheel;
                $tmp[] = [
                    "key" => $wheel->key,
                    "type" => $wheel->type,
                    "value" => $wheel->value,
                    "bg_color" => $wheel->bg_color ?? null,
                    "smile" => $wheel->smile ?? null
                ];
            }

        $slug->config = $tmp;
        $slug->save();

        BusinessLogic::bots()
            ->setBot($bot)
            ->setBotUser($botUser)
            ->setConfig([
                "wheel_of_fortune" => $tmp
            ]);

        return new BotMenuSlugResource($slug);
    }


    public function wheelOfFortune(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $botUser = BotManager::bot()->currentBotUser();

        $mainImage = (Collection::make($config[1])
            ->where("key", "main_image")
            ->first())["value"] ?? null;

        $profileScriptId = (Collection::make($config[1])
            ->where("key", "profile_id")
            ->first())["value"] ?? null;

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Начать розыгрыш";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        $keyboard = [
            [
                ["text" => $btnText, "web_app" => [
                    "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId&hide_menu#/s/wheel-of-fortune-custom"
                ]],
            ],

        ];

        if (!$botUser->is_vip) {

            $bot = BotManager::bot()->getSelf();

            if (!is_null($profileScriptId)) {

                BotManager::bot()
                    ->runSlug($profileScriptId, $bot, $botUser);

            } else {
                $keyboard = [
                    [
                        ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/$bot->bot_domain?slug=$slugId&hide_menu#/s/new-vip"
                        ]],
                    ],

                ];


                if (is_null($mainImage))
                    \App\Facades\BotManager::bot()
                        ->replyInlineKeyboard("Для начала необходимо заполнить анкету!", $keyboard);
                else
                    \App\Facades\BotManager::bot()
                        ->replyPhoto("Для начала необходимо заполнить анкету!", $mainImage, $keyboard);

            }

            return;
        }


        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                is_null($mainImage) ?
                    InputFile::create(public_path() . "/images/wheel-of-fortune.jpg") :
                    $mainImage,
                $keyboard);


    }

    /**
     * @param mixed $bot
     * @param mixed $botUser
     * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null $slug
     * @return \Illuminate\Http\JsonResponse
     * @throws HttpException
     */
    protected function extractedPreparedPrizes(mixed $bot, mixed $botUser, mixed $slug): \Illuminate\Http\JsonResponse
    {
        if (is_null($bot) || is_null($botUser) || is_null($slug))
            throw new HttpException("Не заданы необходимые параметры функции", 400);

        $maxAttempts = (Collection::make($slug->config ?? [])
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
                    'bot_user_id' => $botUser->id
                ]);

        $action->max_attempts = $maxAttempts;

        if (is_null($action->data))
            $action->current_attempts = 0;

        $action->save();

        return response()->json([
            "action" => new ActionStatusResource($action),
        ]);
    }
}
