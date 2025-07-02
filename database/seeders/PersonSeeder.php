<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('persons')->insert([
            // Smith family (3 people)
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
                'first_name' => 'Anna',
                'infix' => null,
                'last_name' => 'Smith',
                'age' => 33,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Lucas',
                'infix' => null,
                'last_name' => 'Smith',
                'age' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Johnson family (2 people)
            [
                'first_name' => 'Jane',
                'infix' => 'A',
                'last_name' => 'Johnson',
                'age' => 28,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Ella',
                'infix' => null,
                'last_name' => 'Johnson',
                'age' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Brown family (4 people)
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
                'first_name' => 'Sophie',
                'infix' => null,
                'last_name' => 'Brown',
                'age' => 40,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Tom',
                'infix' => null,
                'last_name' => 'Brown',
                'age' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Lisa',
                'infix' => null,
                'last_name' => 'Brown',
                'age' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // White family (2 people)
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
                'first_name' => 'Mila',
                'infix' => null,
                'last_name' => 'White',
                'age' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Davis family (2 people)
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
                'first_name' => 'Sarah',
                'infix' => null,
                'last_name' => 'Davis',
                'age' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Clark family (3 people)
            [
                'first_name' => 'Emily',
                'infix' => null,
                'last_name' => 'Clark',
                'age' => 27,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Noah',
                'infix' => null,
                'last_name' => 'Clark',
                'age' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Olivia',
                'infix' => null,
                'last_name' => 'Clark',
                'age' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
