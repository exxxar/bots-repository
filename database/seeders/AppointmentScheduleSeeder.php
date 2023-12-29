<?php

namespace Database\Seeders;

use App\Models\AppointmentSchedule;
use Illuminate\Database\Seeder;

class AppointmentScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentSchedule::factory()->count(5)->create();
    }
}
