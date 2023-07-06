<?php

namespace Database\Seeders;

use App\Models\ActionStatus;
use Illuminate\Database\Seeder;

class ActionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActionStatus::factory()->count(5)->create();
    }
}
