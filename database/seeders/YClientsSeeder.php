<?php

namespace Database\Seeders;

use App\Models\YClients;
use Illuminate\Database\Seeder;

class YClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        YClients::factory()->count(5)->create();
    }
}
