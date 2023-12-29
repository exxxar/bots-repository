<?php

namespace Database\Seeders;

use App\Models\AppointmentService;
use Illuminate\Database\Seeder;

class AppointmentServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentService::factory()->count(5)->create();
    }
}
