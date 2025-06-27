<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyMemberSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('family_members')->insert([
            [
                'adults' => 2,
                'children' => 1,
                'babies' => 0,
                'is_active' => true,
                'note' => 'Family with two adults and one child',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'adults' => 1,
                'children' => 0,
                'babies' => 1,
                'is_active' => true,
                'note' => 'Single parent with a baby',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'adults' => 2,
                'children' => 2,
                'babies' => 0,
                'is_active' => true,
                'note' => 'Family with two adults and two children',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'adults' => 1,
                'children' => 1,
                'babies' => 1,
                'is_active' => true,
                'note' => 'Single parent with one child and a baby',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'adults' => 2,
                'children' => 0,
                'babies' => 0,
                'is_active' => true,
                'note' => 'Couple without children',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'adults' => 2,
                'children' => 1,
                'babies' => 1,
                'is_active' => true,
                'note' => 'Family with two adults, one child, and a baby',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
