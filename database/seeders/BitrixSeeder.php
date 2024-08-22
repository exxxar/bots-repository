<?php

namespace Database\Seeders;

use App\Models\Bitrix;
use Illuminate\Database\Seeder;

class BitrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bitrix::factory()->count(5)->create();
    }
}
