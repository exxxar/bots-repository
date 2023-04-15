<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\BotMenuSlug;
use App\Models\CashBackHistory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,

            CompanySeeder::class,
            LocationSeeder::class,
            BotTypeSeeder::class,
            BotSeeder::class,
            BotMenuTemplateSeeder::class,
            BotMenuSlugSeeder::class,
            BotUserSeeder::class,
            CashBackHistorySeeder::class,
            ImageMenuSeeder::class,




        ]);
    }
}
