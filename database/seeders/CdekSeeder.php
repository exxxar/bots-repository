<?php

namespace Database\Seeders;

use App\Models\Cdek;
use Illuminate\Database\Seeder;

class CdekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cdek::factory()->count(5)->create();
    }
}
