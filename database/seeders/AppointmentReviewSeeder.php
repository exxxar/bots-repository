<?php

namespace Database\Seeders;

use App\Models\AppointmentReview;
use Illuminate\Database\Seeder;

class AppointmentReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentReview::factory()->count(5)->create();
    }
}
