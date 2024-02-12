<?php

namespace Database\Seeders;

use App\Models\InlineQuerySlug;
use Illuminate\Database\Seeder;

class InlineQuerySlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InlineQuerySlug::factory()->count(5)->create();
    }
}
