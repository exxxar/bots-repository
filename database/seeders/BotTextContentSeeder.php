<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\BotTextContent;
use Illuminate\Database\Seeder;

class BotTextContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
          ["key"=>"key_1", "value"=>"Главное меню"],
          ["key"=>"key_2", "value"=>"Главное меню (Режим администратора)"],
          ["key"=>"key_3", "value"=>""],
          ["key"=>"key_4", "value"=>""],
        ];


        $bots = Bot::query()->get();

        foreach($bots as $bot) {
            foreach ($data as $item)
                BotTextContent::query()
                    ->create([
                        "key"=>$item["key"],
                        "value"=>$item["value"],
                        "bot_id"=>$bot->id,
                    ]);
        }


    }
}
