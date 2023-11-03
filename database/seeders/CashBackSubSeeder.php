<?php

namespace Database\Seeders;

use App\Models\CashBackSub;
use Illuminate\Database\Seeder;

class CashBackSubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashBackSub::factory()->count(5)->create();
    }
}
