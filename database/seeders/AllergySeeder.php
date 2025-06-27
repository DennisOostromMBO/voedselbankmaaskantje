<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('allergies')->insert([
            [
                'wish_id' => 1,
                'name' => 'gluten',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wish_id' => 2,
                'name' => 'Pindas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wish_id' => 3,
                'name' => 'Schaaldieren',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wish_id' => 4,
                'name' => 'Hazelnoten',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wish_id' => 5,
                'name' => 'Lactose',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
