<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->create([
            'name'=>"Пользователь",
            'slug'=>'user',
        ]);

        Role::query()->create([
            'name'=>"Администратор",
            'slug'=>'administrator',
        ]);

        Role::query()->create([
            'name'=>"Владелец",
            'slug'=>'owner',
        ]);
    }
}
