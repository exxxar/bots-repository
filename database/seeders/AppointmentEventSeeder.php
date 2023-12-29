<?php

namespace Database\Seeders;

use App\Models\AppointmentEvent;
use Illuminate\Database\Seeder;

class AppointmentEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentEvent::factory()->count(5)->create();
    }
}
