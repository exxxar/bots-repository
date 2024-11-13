<?php

namespace Database\Seeders;

use App\Models\TrafficSource;
use Illuminate\Database\Seeder;

class TrafficSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TrafficSource::factory()->count(5)->create();
    }
}
