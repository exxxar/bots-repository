<?php

namespace Database\Seeders;

use App\Models\PaymentHistory;
use Illuminate\Database\Seeder;

class PaymentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentHistory::factory()->count(5)->create();
    }
}
