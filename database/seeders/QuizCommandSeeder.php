<?php

namespace Database\Seeders;

use App\Models\QuizCommand;
use Illuminate\Database\Seeder;

class QuizCommandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QuizCommand::factory()->count(5)->create();
    }
}
