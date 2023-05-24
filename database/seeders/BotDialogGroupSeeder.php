<?php

namespace Database\Seeders;

use App\Models\BotDialogGroup;
use Illuminate\Database\Seeder;

class BotDialogGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotDialogGroup::factory()->count(5)->create();
    }
}
