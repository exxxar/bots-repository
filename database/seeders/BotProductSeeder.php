<?php

namespace Database\Seeders;

use App\Models\BotProduct;
use Illuminate\Database\Seeder;

class BotProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotProduct::factory()->count(5)->create();
    }
}
