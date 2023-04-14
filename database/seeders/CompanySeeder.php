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
        Company::query()->create([
            'title'=>"\"AR COFFEE\" кэш-бэк бот",
            'slug'=>"arcoffee",
            'description'=>"Добро пожаловать!Меня зовут CashMan. Я виртуальный помощник кофейни \"AR coffee\". У нас вы можете купить много сортов натурального кофе вместе с нашей фирменной выпечкой. С помощью данного бота вы можете зарабатывать кешбек, приглашая друзей в нашу кофейню. Подробности в разделе \"Анкета VIP-пользователя\".",
            'address'=>"бул. Шевченко, 13, Донецк",
            'image'=>"d0451060e588ccb84087d.jpg",
            'phones'=>[
              "+7(949)432-06-01",
              "+7(949)432-06-02",
              "+7(949)432-06-03",
            ],
            'links'=>null,
            'email'=>"ar.coffee@your-cashman.ru",
            'schedule'=>null,
            'manager'=>null,
            'is_active'=>true,
        ]);

        Company::query()->create([
            'title'=>"\"ObedyGO\" кэш-бэк бот",
            'slug'=>"obedygo",
            'description'=>"Добро пожаловать!Меня зовут CashMan. Я виртуальный помощник кофейни \"AR coffee\". У нас вы можете купить много сортов натурального кофе вместе с нашей фирменной выпечкой. С помощью данного бота вы можете зарабатывать кешбек, приглашая друзей в нашу кофейню. Подробности в разделе \"Анкета VIP-пользователя\".",
            'address'=>"бул. Шевченко, 13, Донецк",
            'phones'=>[
                "+7(949)432-06-01",
                "+7(949)432-06-02",
                "+7(949)432-06-03",
            ],
            'image'=>"d0451060e588ccb84087d.jpg",
            'links'=>[
                "https://vk.com/math_algo",
                "https://t.me/test",
                "https://instagram.com/test",
            ],
            'email'=>"obedygo@your-cashman.ru",
            'schedule'=>[
                "ПН 10:00 - 23:00",
                "ВТ 10:00 - 23:00",
                "СР 10:00 - 23:00",
                "ЧТ 10:00 - 23:00",
                "ПТ 10:00 - 23:00",
                "СБ 12:00 - 20:00",
                "ВС 12:00 - 16:00",
            ],
            'manager'=>"Анатолий",
            'is_active'=>true,
        ]);
    }
}
