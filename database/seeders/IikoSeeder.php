<?php

namespace Database\Seeders;

use App\Models\Iiko;
use Illuminate\Database\Seeder;

class IikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Iiko::factory()->count(5)->create();
    }
}
