<?php

namespace Database\Seeders;

use App\Models\BotWarning;
use Illuminate\Database\Seeder;

class BotWarningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotWarning::factory()->count(5)->create();
    }
}
