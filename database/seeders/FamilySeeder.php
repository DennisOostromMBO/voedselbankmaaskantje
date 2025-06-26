<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('families')->insert([
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
                'family_member_id' => 2,
                'name' => 'Johnson',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 3,
                'family_member_id' => 3,
                'name' => 'Brown',
                'is_active' => true,
                'note' => 'Brown family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 4,
                'family_member_id' => 4,
                'name' => 'White',
                'is_active' => true,
                'note' => 'White family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 5,
                'family_member_id' => 5,
                'name' => 'Davis',
                'is_active' => true,
                'note' => 'Davis family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 6,
                'family_member_id' => 6,
                'name' => 'Clark',
                'is_active' => true,
                'note' => 'Clark family',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
