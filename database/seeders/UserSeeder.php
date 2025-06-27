<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'person_id' => 1,
                'password' => Hash::make('password'),
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 4,
                'password' => Hash::make('password'),
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 6,
                'password' => Hash::make('password'),
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Assign roles to users (example: user 1 = Directie, user 2 = Magazijnmedewerker, user 3 = Vrijwilliger)
        DB::table('role_user')->insert([
            [
                'user_id' => 1,
                'role_id' => 1, // Directie
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'role_id' => 2, // Magazijnmedewerker
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'role_id' => 3, // Vrijwilliger
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
