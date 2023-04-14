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
    }
}
