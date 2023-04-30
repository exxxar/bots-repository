<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::query()
            ->where("slug", "cashman")
            ->first();

        if (is_null($company))
            Company::query()->create([
                'title' => "CashMan",
                'slug' => "cashman",
                'description' => "Добро пожаловать!Меня зовут CashMan. Я виртуальный помощник по созданию продающих Телеграмм-Ботов.",
                'address' => "бул. Шевченко, 13, Донецк",
                'image' => "d0451060e588ccb84087d.jpg",
                'phones' => [
                    "+7(949)432-06-01",
                    "+7(949)432-06-02",
                    "+7(949)432-06-03",
                ],
                'links' => null,
                'email' => "inbox@your-cashman.ru",
                'schedule' => null,
                'manager' => "Егор",
                'is_active' => true,
            ]);

        /*   Company::query()->create([
               'title'=>"\"Delivery Rocket\" сервис доставки",
               'slug'=>"delivery_rocket",
               'description'=>"Добро пожаловать в систему управления доставкой Delivery Rocket от CashMan",
               'address'=>"бул. Шевченко, 13, Донецк",
               'phones'=>[
                   "+7(949)432-06-01",
                   "+7(949)432-06-02",
                   "+7(949)432-06-03",
               ],
               'image'=>"d0451060e588ccb84087d.jpg",
               'links'=>[
                   "https://vk.com/delivery_rocket",
                   "https://t.me/delivery_rocket",
                   "https://instagram.com/delivery_rocket",
               ],
               'email'=>"delivery-rocket@your-cashman.ru",
               'schedule'=>[
                   "ПН 10:00 - 20:00",
                   "ВТ 10:00 - 20:00",
                   "СР 10:00 - 20:00",
                   "ЧТ 10:00 - 20:00",
                   "ПТ 10:00 - 20:00",
                   "СБ 12:00 - 20:00",
                   "ВС 12:00 - 16:00",
               ],
               'manager'=>"Анатолий",
               'is_active'=>true,
           ]);*/
    }
}
