<?php

namespace App\Console\Commands;

use App\Classes\BotMethods;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class CheckCashback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:cashback-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ежемесячное сгорание CashBack';

    /**
     * Execute the console command.
     * @throws ValidationException
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);
        $bots = Bot::query()
            ->get();

        foreach ($bots as $bot) {

            $percent = $bot->cashback_fire_percent ?? 0;
            $period = $bot->cashback_fire_period ?? 0;

            if ($period == 0 || $percent == 0)
                continue;

            $admin = BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_admin", true)
                ->orderBy("updated_at", "desc")
                ->first();

            if (is_null($admin))
                continue;

            $cashbacks = CashBack::query()
                ->with(["botUser"])
                ->where("bot_id", $bot->id)
                ->where("amount",">", 0)
                ->get();

            foreach ($cashbacks as $cashback) {

                $canFired = Carbon::parse($cashback->fired_at)
                    ->addDays($period??0) <= Carbon::now();

                if (!$canFired)
                    continue;

                $userTelegramChatId = $cashback->botUser->telegram_chat_id ?? null;

                if (is_null($userTelegramChatId))
                    continue;

                $amount = ($cashback->amount * $percent)/100;

                BusinessLogic::administrative()
                    ->setBot($bot ?? null)
                    ->setBotUser($admin)
                    ->removeCashBack([
                        "user_telegram_chat_id" => $userTelegramChatId,
                        "amount" => $amount,
                        "info" => "Сгорание $percent % CashBack",
                    ]);

                $cashbacks->fired_at = Carbon::now();
                $cashback->save();
            }

            ini_set('max_execution_time', 300);
        }
    }
}
