<?php

namespace Database\Seeders;

use App\Models\BotType;
use Illuminate\Database\Seeder;

class BotTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotType::query()->create([
            'title'=>"Рестораны",
            'slug'=>"restaurant",
            'is_active'=>true
        ]);

        BotType::query()->create([
            'title'=>"Воронки продаж",
            'slug'=>"sales_funnel",
            'is_active'=>true
        ]);

        BotType::query()->create([
            'title'=>"Магазины",
            'slug'=>"shops",
            'is_active'=>true
        ]);

        BotType::query()->create([
            'title'=>"Сервис доставки (клиент доставщика)",
            'slug'=>"delivery_service_for_deliveryman",
            'is_active'=>true
        ]);

        BotType::query()->create([
            'title'=>"Другое",
            'slug'=>"other",
            'is_active'=>true
        ]);
    }
}
