<?php

namespace Database\Seeders;

use App\Models\BotMedia;
use Illuminate\Database\Seeder;

class BotMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotMedia::factory()->count(5)->create();
    }
}
