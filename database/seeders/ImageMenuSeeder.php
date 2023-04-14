<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\ImageMenu;
use Illuminate\Database\Seeder;

class ImageMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bot = Bot::query()
            ->where('bot_domain', "obedy_go_bot")
            ->first();

        ImageMenu::query()->create([
            'title'=>"test",
            'description'=>"test",
            'image'=>"https://gcdn.tomesto.ru/img/place/000/026/318/kafe-vstrecha-na-kirova_2d710_full-320510.jpg",
            'info_link'=>null,
            'bot_id'=>$bot->id,
            'product_count'=>10,
            'location_id'=>1,
        ]);
    }
}
