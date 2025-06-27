<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VolunteerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('volunteers')->insert([
            [
                'role_id' => 1,
                'aantal' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'aantal' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'aantal' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
