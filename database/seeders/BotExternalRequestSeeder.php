<?php

namespace Database\Seeders;

use App\Models\BotExternalRequest;
use Illuminate\Database\Seeder;

class BotExternalRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotExternalRequest::factory()->count(5)->create();
    }
}
