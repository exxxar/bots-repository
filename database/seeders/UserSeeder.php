<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()
            ->where("slug","owner")
            ->first();

        User::query()->create([
            'email'=>"exxxar@gmail.com",
            'phone'=>"79494320661",
            'name'=>"Alex",
            'password'=>bcrypt("password"),

            'role_id'=>$role->id,
        ]);

        User::query()->create([
            'email'=>"admin@admin.com",
            'phone'=>"1234567",
            'name'=>"Admin",
            'password'=>bcrypt("password"),

            'role_id'=>$role->id,
        ]);
    }
}
