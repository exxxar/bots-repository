<?php

namespace App\Console\Commands;

use App\Classes\BotMethods;
use App\Facades\BotManager;
use App\Models\Bot;
use App\Models\BotUser;
use Illuminate\Console\Command;

class CheckBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:balance-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ежедневное списание денежных средств с активных ботов';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);
        $bots = Bot::query()
            ->get();

        foreach ($bots as $bot) {
            if ($bot->balance > 0) {
                $bot->balance -= $bot->tax_per_day;
                $bot->save();

                if ($bot->balance < 0) {
                    $admin = BotUser::query()
                        ->where("bot_id", $bot->id)
                        ->where("is_admin", true)
                        ->orderBy("updated_at", "desc")
                        ->first();

                    if (!is_null($admin))
                        \App\Facades\BotMethods::bot()
                            ->whereBot($bot)
                            ->sendMessage($admin->telegram_chat_id, "Внимание!Ваш баланс составил $bot->balance руб. Текущий баланс системы ниже 0! Система не доступна для пользователей. Ваш тариф  $bot->tax_per_day руб\сутки");
                }


            }


        }

        ini_set('max_execution_time', 300);
    }
}
