<?php

namespace Database\Seeders;

use App\Models\InlineQueryItem;
use Illuminate\Database\Seeder;

class InlineQueryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InlineQueryItem::factory()->count(5)->create();
    }
}
