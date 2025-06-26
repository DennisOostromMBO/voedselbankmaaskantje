<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('wishes')->insert([
            [
                'customer_id' => 1,
                'choices' => 'Vegetarisch',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 2,
                'choices' => 'Veganistisch',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 3,
                'choices' => 'Geen varkensvlees',
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 4,
                'choices' => null,
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 5,
                'choices' => null,
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 6,
                'choices' => null,
                'is_active' => true,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
