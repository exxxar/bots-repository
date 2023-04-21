<?php

namespace Database\Seeders;

use App\Models\BotProductCategory;
use Illuminate\Database\Seeder;

class BotProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotProductCategory::factory()->count(5)->create();
    }
}
