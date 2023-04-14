<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\BotUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class BotUserSeeder extends Seeder
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
            BotUser::query()->create([
                'bot_id' => $bot->id,
                'user_id' => $user->id,
                'parent_id' => null,
                'is_vip' => true,
                'is_admin' => true,
                'is_work' => true,
                'user_in_location' => false,
                'fio_from_telegram'=>"Admin",
                'telegram_chat_id'=>"484698703",
            ]);
    }
}
