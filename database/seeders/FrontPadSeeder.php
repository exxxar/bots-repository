<?php

namespace Database\Seeders;

use App\Models\FrontPad;
use Illuminate\Database\Seeder;

class FrontPadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FrontPad::factory()->count(5)->create();
    }
}
