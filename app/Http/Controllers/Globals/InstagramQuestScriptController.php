<?php

namespace App\Http\Controllers\Globals;

use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionStatusResource;
use App\Models\ActionStatus;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class InstagramQuestScriptController extends Controller
{
    const SCRIPT = "global_instagram_quest";

    const KEY_MAX_ATTEMPTS = "max_attempts";
    const KEY_CALLBACK_CHANNEL_ID = "callback_channel_id";
    const KEY_RULES_TEXT = "rules_text";
    const KEY_RESULT_MESSAGE = "result_message";
    const KEY_MAIN_TEXT = "main_text";
    const KEY_BTN_TEXT = "btn_text";

    public function instagramQuestCallback(Request $request, $botDomain)
    {
        $request->validate([
            "telegram_chat_id" => "required",
            "name" => "required",
            "phone" => "required",
            "comment" => "required",
        ]);

        $bot = \App\Models\Bot::query()
            ->with(["company"])
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->telegram_chat_id)
            ->first();

        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", self::SCRIPT)
            ->orderBy("updated_at", "desc")
            ->first();

        $imageName = $request->image ?? null;
        $companySlug = $bot->company->slug;
        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;


            $file->storeAs("/public/companies/$companySlug/$imageName");

        }

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", self::KEY_MAX_ATTEMPTS)
            ->first())["value"] ?? 1;

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", self::KEY_CALLBACK_CHANNEL_ID)
            ->first())["value"] ?? -1;

        $winMessage = (Collection::make($slug->config)
            ->where("key", self::KEY_RESULT_MESSAGE)
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
                    'current_attempts' => 0
                ]);

        $action->current_attempts++;
        if ($action->current_attempts >= $maxAttempts)
            $action->completed_at = Carbon::now();

        $action->save();

        $winnerName = $request->name ?? 'Имя не указано';
        $winnerPhone = $request->phone ?? 'Телефон не указан';
        $winnerComment = $request->comment ?? 'Комментарий не указан';

        $path = storage_path("app/public") . "/companies/$companySlug/" . ($imageName ?? 'noimage.jpg');
        $file = InputFile::create(
            file_exists($path) ?
                $path :
                public_path() . "/images/cashman.jpg"
        );

        BotMethods::bot()
            ->whereDomain($botDomain)
            ->sendMessage($botUser
                ->telegram_chat_id,
                sprintf($winMessage, $winnerName))
            ->sendPhoto($callbackChannel,
                "Участника $winnerPhone ($winnerName) принял участие в InstagramQuest - свяжитесь с ним для дальнейших указаний",
                $file
            )
            ->sendMessage($callbackChannel, "Комментарий участника: $winnerComment");

        return response()->noContent();
    }

    public function instagramQuestPrepare(Request $request, $botDomain)
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
            ->where("bot_id", $bot->id)
            ->where("slug", self::SCRIPT)
            ->orderBy("updated_at", "desc")
            ->first();

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

        return new ActionStatusResource($action);
    }

    public function instagramQuestForm($botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();


        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", self::SCRIPT)
            ->orderBy("updated_at", "desc")
            ->first();


        $rules = Collection::make($slug->config)
            ->where("key", self::KEY_RULES_TEXT)
            ->first();

        Inertia::setRootView("bot");


        return Inertia::render('BotPages/InstagramQuest', [
            'bot' => json_decode($bot->toJson()),
            'rules' => $rules["value"] ?? null,
        ]);

    }

    public function instagramQuest(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", self::KEY_MAIN_TEXT)
            ->first())["value"] ?? "Участвуй в квесте и получай призы";

        $btnText = (Collection::make($config[1])
            ->where("key", self::KEY_BTN_TEXT)
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Перейти к выполнению задания";

        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/cashman-quest.png"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/global-scripts/instagram-quest/$bot->bot_domain"
                        ]],
                    ],

                ]);
    }
}
