<?php

namespace App\Console\Commands;

use App\Classes\BotMethods;
use App\Facades\BotManager;
use App\Models\Bot;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:birthday-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ежедневная проверка дня рождения пользователя';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);
        $botUsers = BotUser::query()
            ->with(["bot"])
            ->whereNotNull("birthday")
            ->whereNotNull("phone")
            ->whereBetween("age", [18, 100])
            ->get();

        $tmp = "Сегодня ДР у следующих пользователей:\n";
        foreach ($botUsers as $botUser) {

            $daysBefore = abs(Carbon::parse($botUser->birthday)->timestamp - Carbon::now()->timestamp) / 86400;

            if ($daysBefore == 0) {
                $name = \App\Facades\BotMethods::prepareUserName($botUser);
                $tgId = $botUser->telegram_chat_id;
                $username = $botUser->username ?? "ник не указан";
                $phone = $botUser->phone ?? "телефон не указан";
                $birthday = Carbon::parse($botUser->birthday)->format("Y-m-d H:i:s");
                $tmp .= "$birthday $name $phone $birthday (@$username)\n";

                $botUser->age = Carbon::now()->year - Carbon::parse($botUser->birthday)->year;
                $botUser->save();
            }
        }

        $bot = $botUser->bot;

        $thread = $bot->topics["actions"] ?? null;

        $channel = $bot->order_channel ?? $bot->main_channel ?? null;

        if (!is_null($channel))
            \App\Facades\BotMethods::bot()
                ->whereBot($bot)
                ->sendMessage($channel, $tmp, $thread);


        ini_set('max_execution_time', 300);
    }
}
