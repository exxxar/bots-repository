<?php

namespace Database\Seeders;

use App\Models\QuizAnswer;
use Illuminate\Database\Seeder;

class QuizAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuizAnswer::factory()->count(5)->create();
    }
}
