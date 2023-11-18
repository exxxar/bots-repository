<?php

namespace Database\Seeders;

use App\Models\FoodConstructor;
use Illuminate\Database\Seeder;

class FoodConstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodConstructor::factory()->count(5)->create();
    }
}
