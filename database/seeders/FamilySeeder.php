<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('families')->insert([
            // Smith family (persons 1,2,3)
            [
                'person_id' => 1,
                'family_member_id' => 1,
                'name' => 'Smith',
                'is_active' => true,
                'note' => 'First family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 2,
                'family_member_id' => 1,
                'name' => 'Smith',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 3,
                'family_member_id' => 1,
                'name' => 'Smith',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Johnson family (persons 4,5)
            [
                'person_id' => 4,
                'family_member_id' => 2,
                'name' => 'Johnson',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 5,
                'family_member_id' => 2,
                'name' => 'Johnson',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Brown family (persons 6,7,8,9)
            [
                'person_id' => 6,
                'family_member_id' => 3,
                'name' => 'Brown',
                'is_active' => true,
                'note' => 'Brown family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 7,
                'family_member_id' => 3,
                'name' => 'Brown',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 8,
                'family_member_id' => 3,
                'name' => 'Brown',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 9,
                'family_member_id' => 3,
                'name' => 'Brown',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // White family (persons 10,11)
            [
                'person_id' => 10,
                'family_member_id' => 4,
                'name' => 'White',
                'is_active' => true,
                'note' => 'White family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 11,
                'family_member_id' => 4,
                'name' => 'White',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Davis family (persons 12,13)
            [
                'person_id' => 12,
                'family_member_id' => 5,
                'name' => 'Davis',
                'is_active' => true,
                'note' => 'Davis family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 13,
                'family_member_id' => 5,
                'name' => 'Davis',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Clark family (persons 14,15,16)
            [
                'person_id' => 14,
                'family_member_id' => 6,
                'name' => 'Clark',
                'is_active' => true,
                'note' => 'Clark family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 15,
                'family_member_id' => 6,
                'name' => 'Clark',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 16,
                'family_member_id' => 6,
                'name' => 'Clark',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
