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


                $channel = $bot->order_channel ?? $bot->main_channel ?? null;

                if ($bot->balance > 0 && $bot->balance < 1000) {
                    $daysBefore = round( $bot->balance  / $bot->tax_per_day);
                    \App\Facades\BotMethods::bot()
                        ->whereBot($bot)
                        ->sendMessage($channel, "Внимание!Ваш баланс составил $bot->balance руб. Ваш тариф  $bot->tax_per_day руб\сутки. Необходимо произвести оплату в течении $daysBefore дней.");

                }

                if ($bot->balance < 0) {
                    \App\Facades\BotMethods::bot()
                        ->whereBot($bot)
                        ->sendMessage($channel, "Внимание!Ваш баланс составил $bot->balance руб. Текущий баланс системы ниже 0! Система не доступна для пользователей. Ваш тариф  $bot->tax_per_day руб\сутки");
                }


            }


        }

        ini_set('max_execution_time', 300);
    }
}
