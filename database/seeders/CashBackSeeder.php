<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\CashBack;
use App\Models\User;
use Illuminate\Database\Seeder;

class CashBackSeeder extends Seeder
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

        foreach ($bots as $bot)
            CashBack::query()->create([
                'user_id' => $user->id,
                'bot_id' => $bot->id,
                'amount' => random_int(100, 3000)
            ]);
    }
}
