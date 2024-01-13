<?php

namespace Database\Seeders;

use App\Models\QuizResult;
use Illuminate\Database\Seeder;

class QuizResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuizResult::factory()->count(5)->create();
    }
}
