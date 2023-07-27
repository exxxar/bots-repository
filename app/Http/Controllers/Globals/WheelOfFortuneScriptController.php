<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\BotSecurityResource;
use App\Models\ActionStatus;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use ReflectionClass;
use Telegram\Bot\FileUpload\InputFile;

class WheelOfFortuneScriptController extends Controller
{
    const SCRIPT = "global_wheel_of_fortune";

    const KEY_MAX_ATTEMPTS = "max_attempts";
    const KEY_CALLBACK_CHANNEL_ID = "callback_channel_id";
    const KEY_RULES_TEXT = "rules_text";
    const KEY_WIN_MESSAGE = "win_message";
    const KEY_WHEEL_TEXT = "wheel_text";
    const KEY_MAIN_TEXT = "main_text";
    const KEY_BTN_TEXT = "btn_text";


    public function formWheelOfFortuneCallback(Request $request, $scriptId, $botDomain)
    {
        $request->validate([
            "telegram_chat_id" => "required",
            "name" => "required",
            "phone" => "required",
            "win" => "required"
        ]);

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->telegram_chat_id)
            ->first();

        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", self::SCRIPT)
            ->where("id", $scriptId)
            ->first();

        if (is_null($slug))
            return response()->noContent(404);

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", self::KEY_MAX_ATTEMPTS)
            ->first())["value"] ?? 1;

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", self::KEY_CALLBACK_CHANNEL_ID)
            ->first())["value"] ??
            $bot->order_channel ??
            $bot->main_channel ??
            env("BASE_ADMIN_CHANNEL");

        $winMessage = (Collection::make($slug->config)
            ->where("key", self::KEY_WIN_MESSAGE)
            ->first())["value"] ?? "%s, вы приняли участие в розыгрыше и выиграли приз под номером %s. Наш менеджер свяжется с вами в ближайшее время!";

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
                    'current_attempts' => 0
                ]);

        $action->current_attempts++;
        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();

        $winNumber = $request->win ?? 0;
        $winnerName = $request->name ?? 'Имя не указано';
        $winnerPhone = $request->phone ?? 'Телефон не указан';

        $tmp = $action->data ?? [];

        $tmp[] = (object)[
            "name" => $winnerName,
            "win" => $winNumber,
            "phone" => $winnerPhone,
            "answered_at" => null,
            "answered_by" => null,
        ];

        $action->data = $tmp;

        $action->save();

        BotMethods::bot()
            ->whereDomain($botDomain)
            ->sendMessage($botUser
                ->telegram_chat_id,
                sprintf("%s, вы приняли участие в розыгрыше и выиграли приз под номером %s. Наш менеджер свяжется с вами в ближайшее время!", $winnerName, $winNumber))
            ->sendMessage($callbackChannel,
                "Участника $winnerPhone ($winnerName) принял участие в розыгрыше и выиграл приз №$winNumber - свяжитесь с ним для дальнейших указаний");

        return response()->noContent();
    }

    public function loadData(Request $request, $scriptId, $botDomain)
    {

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();


        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("id", $scriptId)
            ->where("slug", self::SCRIPT)
            ->first();


        if (is_null($slug))
            return response()->noContent(404);

        $wheels = Collection::make($slug->config)
            ->where("key", self::KEY_WHEEL_TEXT)
            ->toArray();

        $rules = Collection::make($slug->config)
            ->where("key", self::KEY_RULES_TEXT)
            ->first();

        return response()->json(
            [
                "wheels" => array_values($wheels),
                'rules' => $rules["value"] ?? null,
            ]

        );
    }

    public function formWheelOfFortunePrepare(Request $request, $scriptId, $botDomain)
    {
        $request->validate([
            "telegram_chat_id" => "required",
        ]);

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->telegram_chat_id)
            ->first();;

        $slug = BotMenuSlug::query()
            ->where("id", $scriptId)
            ->first();

        if (is_null($slug))
            return response()->noContent(404);

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", self::KEY_MAX_ATTEMPTS)
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
                    'current_attempts' => 0
                ]);

        return response()->json([
            "action" => new ActionStatusResource($action),

        ]);
    }

    /* public function formWheelOfFortune(Request $request, $scriptId, $botDomain)
     {

         $bot = \App\Models\Bot::query()
             ->with(["company", "imageMenus"])
             ->where("bot_domain", $botDomain)
             ->first();


         $slug = BotMenuSlug::query()
             ->where("id", $scriptId)
             ->where("bot_id", $bot->id)
             ->where("slug", self::SCRIPT)
             ->first();

         if (is_null($slug)) {
             Inertia::setRootView("bot");
             return Inertia::render('Error');
         }


         Inertia::setRootView("shop");

         return Inertia::render('Shop/Main', [
             'bot' => BotSecurityResource::make($bot),
             'slug_id' => $slug->id,
         ]);

     }*/

    public function wheelOfFortune(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", self::KEY_MAIN_TEXT)
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $btnText = (Collection::make($config[1])
            ->where("key", self::KEY_BTN_TEXT)
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Начать розыгрыш";

        $slugId = (Collection::make($config[1])
            ->where("key", "slug_id")
            ->first())["value"];

        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/cashman-wheel-of-fortune.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/global-scripts/$slugId/interface/$bot->bot_domain#wheel-of-fortune"
                        ]],
                    ],

                ]);
    }
}
