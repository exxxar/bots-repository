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
        $botType = BotType::query()
            ->where("slug", "cashback")
            ->first();

        if (is_null($botType))
            BotType::query()->create([
                'title' => "Системы CashBack",
                'slug' => "cashback",
                'is_active' => true
            ]);


        $botType = BotType::query()
            ->where("slug", "sales_funnel")
            ->first();

        if (is_null($botType))
            BotType::query()->create([
                'title' => "Воронки продаж",
                'slug' => "sales_funnel",
                'is_active' => true
            ]);

        $botType = BotType::query()
            ->where("slug", "shops")
            ->first();

        if (is_null($botType))
            BotType::query()->create([
                'title' => "Магазины",
                'slug' => "shops",
                'is_active' => true
            ]);


        $botType = BotType::query()
            ->where("slug", "delivery_service_for_deliveryman")
            ->first();

        if (is_null($botType))
            BotType::query()->create([
                'title' => "Сервис доставки (клиент доставщика)",
                'slug' => "delivery_service_for_deliveryman",
                'is_active' => true
            ]);


        $botType = BotType::query()
            ->where("slug", "manager_system")
            ->first();

        if (is_null($botType))
            BotType::query()->create([
                'title' => "Система менеджеров",
                'slug' => "manager_system",
                'is_active' => true
            ]);


        $botType = BotType::query()
            ->where("slug", "other")
            ->first();

        if (is_null($botType))
            BotType::query()->create([
                'title' => "Другое",
                'slug' => "other",
                'is_active' => true
            ]);
    }
}
