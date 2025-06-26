<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('persons')->insert([
            [
                'first_name' => 'John',
                'infix' => null,
                'last_name' => 'Smith',
                'age' => 35,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'infix' => 'A.',
                'last_name' => 'Johnson',
                'age' => 28,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Peter',
                'infix' => null,
                'last_name' => 'Brown',
                'age' => 42,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Linda',
                'infix' => null,
                'last_name' => 'White',
                'age' => 31,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Mark',
                'infix' => null,
                'last_name' => 'Davis',
                'age' => 22,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Emily',
                'infix' => null,
                'last_name' => 'Clark',
                'age' => 27,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
