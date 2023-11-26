<?php

namespace Database\Seeders;

use App\Models\BotCustomFieldSetting;
use Illuminate\Database\Seeder;

class BotCustomFieldSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotCustomFieldSetting::factory()->count(5)->create();
    }
}
