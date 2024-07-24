<?php

namespace Database\Seeders;

use App\Models\SubShop;
use Illuminate\Database\Seeder;

class SubShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubShop::factory()->count(5)->create();
    }
}
