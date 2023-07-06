<?php

namespace App\Http\Controllers;

use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Models\ActionStatus;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Telegram\Bot\FileUpload\InputFile;

class GlobalScriptsController extends Controller
{
    //

    /*
     * max_attempts
     * callback_channel_id
     * rules_text
     * win_message
     */
    public function formWheelOfFortuneCallback(Request $request, $botDomain)
    {
        $request->validate([
            "tg" => "required",
            "name" => "required",
            "phone" => "required",
            "win" => "required"
        ]);

        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotUser::query()
            ->where("bot_id", $bot->id)
            ->where("telegram_chat_id", $request->tg["id"])
            ->first();

        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_wheel_of_fortune")
            ->orderBy("updated_at", "desc")
            ->first();

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $callbackChannel = (Collection::make($slug->config)
            ->where("key", "callback_channel_id")
            ->first())["value"] ?? -1;

        $winMessage = (Collection::make($slug->config)
            ->where("key", "win_message")
            ->first())["value"] ?? "Вы приняли участие в розыгрыше и выиграли приз под номером %s. Наш менеджер свяжется с вами в ближайшее время!";

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("script", $slug->slug)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'script' => $slug->slug,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0
                ]);

        $action->current_attempts++;
        if ($action->current_attempts === $maxAttempts)
            $action->completed_at = Carbon::now();

        $action->save();

        $winNumber = $request->win ?? 0;
        $winnerName = $request->name ?? 'Имя не указано';
        $winnerPhone = $request->phone ?? 'Телефон не указан';

        BotMethods::bot()
            ->whereDomain($botDomain)
            ->sendMessage($botUser
                ->telegram_chat_id,
                printf($winMessage, $winNumber))
            ->sendMessage($callbackChannel,
                "Участника $winnerPhone ($winnerName) принял участие в розыгрыше и выиграл приз №$winNumber - свяжитесь с ним для дальнейших указаний");

        return response()->noContent();
    }

    public function formWheelOfFortune($botDomain)
    {
        $bot = \App\Models\Bot::query()
            ->where("bot_domain", $botDomain)
            ->first();

        $botUser = BotManager::bot()->currentBotUser();


        $slug = BotMenuSlug::query()
            ->where("bot_id", $bot->id)
            ->where("slug", "global_wheel_of_fortune")
            ->orderBy("updated_at", "desc")
            ->first();

        $maxAttempts = (Collection::make($slug->config)
            ->where("key", "max_attempts")
            ->first())["value"] ?? 1;

        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("script", $slug->slug)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'script' => $slug->slug,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0
                ]);

        $wheels = Collection::make($slug->config)
            ->where("key", "wheel_text")
            ->toArray();

        $rules = Collection::make($slug->config)
            ->where("key", "rules_text")
            ->first();

        Inertia::setRootView("bot");

        if ($action->current_attempts < $action->max_attempts)
            return Inertia::render('BotPages/WheelOfFortune', [
                'bot' => json_decode($bot->toJson()),
                "wheels" => array_values($wheels),
                'rules' => $rules->value ?? null,
                'action' => json_decode($action->toJson()),
            ]);
        else
            return Inertia::render('BotPages/Warning', [
                'message' => "Вы исчерпали лимит попыток!"
            ]);
    }

    public function wheelOfFortune(...$config)
    {

        $bot = BotManager::bot()->getSelf();

        $mainText = (Collection::make($config[1])
            ->where("key", "main_text")
            ->first())["value"] ?? "Начни розыгрыш и получи свои призы!";

        $btnText = (Collection::make($config[1])
            ->where("key", "btn_text")
            ->first())["value"] ?? "\xF0\x9F\x8E\xB2Заполнить анкету";

        \App\Facades\BotManager::bot()
            ->replyPhoto($mainText,
                InputFile::create(public_path() . "/images/cashman2.jpg"),
                [
                    [
                        ["text" => $btnText, "web_app" => [
                            "url" => env("APP_URL") . "/global-scripts/wheel-of-fortune/$bot->bot_domain"
                        ]],
                    ],

                ]);
    }
}
