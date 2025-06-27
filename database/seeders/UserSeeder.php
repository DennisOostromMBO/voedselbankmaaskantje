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
    }
}
