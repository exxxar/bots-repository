<?php

namespace Database\Seeders;

use App\Models\BotPage;
use Illuminate\Database\Seeder;

class BotPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotPage::factory()->count(5)->create();
    }
}
