<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class CashBackHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $bots = Bot::query()
            ->get();

        $user = User::query()
            ->where('email', "exxxar@gmail.com")
            ->first();

        foreach ($bots as $bot) {
            $sum = 0;
            for ($i = 0; $i < random_int(10, 100); $i++) {
                $money = random_int(1000, 30000);
                $sum += $money * 0.1;
                CashBackHistory::query()->create([
                    'money_in_check' => $money,
                    'description' => "test",
                    'amount' => $money * 0.1,
                    'operation_type' => random_int(0, 1),
                    'user_id' => $user->id,
                    'bot_id' => $bot->id,
                    'employee_id' => 1,
                ]);


            }

            CashBack::query()->create([
                'user_id' => $user->id,
                'bot_id' => $bot->id,
                'amount' => $sum
            ]);
        }



    }
}
