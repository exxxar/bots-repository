<?php

namespace Database\Seeders;

use App\Models\Geo;
use Illuminate\Database\Seeder;

class GeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Geo::factory()->count(5)->create();
    }
}
