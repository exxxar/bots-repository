<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\BotType;
use App\Models\Company;
use Illuminate\Database\Seeder;

class BotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::query()
            ->where("slug","obedygo")
            ->first();

        $botType = BotType::query()
            ->where("slug","restaurant")
            ->first();

        Bot::query()->create([
            'company_id'=>$company->id,
            'bot_domain'=>"obedy_go_bot",
            'bot_token'=>"6197194236:AAFDvk5X1_GhFg9b_ObVcZTq5SJXsre_6Yw",
            'bot_token_dev'=>"6197194236:AAFDvk5X1_GhFg9b_ObVcZTq5SJXsre_6Yw",
            'order_channel'=>null,
            'balance'=>10000,
            'tax_per_day'=>10,
            'image'=>"d0451060e588ccb84087d.jpg",
            'description'=>"Тестовое описание бота",
            'info_link'=>"https://telegra.ph/O-nas-06-02-4",
            'social_links'=>[
                [
                    "title"=>"Наш instagram",
                    "url"=>"http://instagram.com/fastoran"
                ],
                [
                    "title"=>"Наш профиль в ВК",
                    "url"=>"https://vk.com/math_algo"
                ]
            ],
            'is_active'=>true,
            'bot_type_id'=>$botType->id,
            'level_1'=>7,
            'level_2'=>2,
            'level_3'=>1,
        ]);

        Bot::query()->create([
            'company_id'=>$company->id,
            'bot_domain'=>"isushibot",
            'bot_token'=>"1050575583:AAEuI5StQcxhNgeXRqfo_VqUG3mzhAWt0V4",
            'bot_token_dev'=>"1050575583:AAEuI5StQcxhNgeXRqfo_VqUG3mzhAWt0V4",
            'order_channel'=>null,
            'balance'=>10000,
            'tax_per_day'=>10,
            'image'=>"",
            'description'=>"",
            'info_link'=>"",
            'is_active'=>true,
            'bot_type_id'=>$botType->id,
            'level_1'=>7,
            'level_2'=>2,
            'level_3'=>1,
        ]);

        $company = Company::query()
            ->where("slug","delivery_rocket")
            ->first();

        $botType = BotType::query()
            ->where("slug","delivery_service_for_deliveryman")
            ->first();

        Bot::query()->create([
            'company_id'=>$company->id,
            'bot_domain'=>"deliveryrocketbot",
            'bot_token'=>"6185612733:AAGjyGTQrm9jqhuxmhjftlPOCxWmYXM5860",
            'bot_token_dev'=>"6185612733:AAGjyGTQrm9jqhuxmhjftlPOCxWmYXM5860",
            'order_channel'=>null,
            'balance'=>10000,
            'tax_per_day'=>10,
            'image'=>"d0451060e588ccb84087d.jpg",
            'description'=>"Тестовое описание бота",
            'info_link'=>"https://telegra.ph/O-nas-06-02-4",
            'social_links'=>[
                [
                    "title"=>"Наш instagram",
                    "url"=>"http://instagram.com/deliveryrocketbot"
                ],
                [
                    "title"=>"Наш профиль в ВК",
                    "url"=>"https://vk.com/deliveryrocketbot"
                ]
            ],
            'is_active'=>true,
            'bot_type_id'=>$botType->id,
            'level_1'=>0,
            'level_2'=>0,
            'level_3'=>0,
        ]);


    }
}
