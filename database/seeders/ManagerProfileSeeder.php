<?php

namespace Database\Seeders;

use App\Models\ManagerProfile;
use Illuminate\Database\Seeder;

class ManagerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ManagerProfile::factory()->count(5)->create();
    }
}
