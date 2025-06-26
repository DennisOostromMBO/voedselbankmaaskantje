<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'family_id' => 1,
                'number' => 'CUST0001',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_id' => 2,
                'number' => 'CUST0002',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_id' => 3,
                'number' => 'CUST0003',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_id' => 4,
                'number' => 'CUST0004',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_id' => 5,
                'number' => 'CUST0005',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_id' => 6,
                'number' => 'CUST0006',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
