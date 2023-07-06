<?php

namespace Database\Seeders;

use App\Models\AmoCrm;
use Illuminate\Database\Seeder;

class AmoCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AmoCrm::factory()->count(5)->create();
    }
}
