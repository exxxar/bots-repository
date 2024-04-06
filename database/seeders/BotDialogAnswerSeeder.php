<?php

namespace Database\Seeders;

use App\Models\BotDialogAnswer;
use Illuminate\Database\Seeder;

class BotDialogAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotDialogAnswer::factory()->count(5)->create();
    }
}
