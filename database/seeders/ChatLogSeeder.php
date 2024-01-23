<?php

namespace Database\Seeders;

use App\Models\ChatLog;
use Illuminate\Database\Seeder;

class ChatLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChatLog::factory()->count(5)->create();
    }
}
