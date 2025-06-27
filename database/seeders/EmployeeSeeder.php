<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'role_id' => 1,
                'is_active' => true,
                'note' => 'Davis family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'is_active' => true,
                'note' => 'Davis family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'is_active' => true,
                'note' => 'Davis family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
