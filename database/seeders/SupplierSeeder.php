<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'supplier_name' => 'Food Supplier A',
                'contact_number' => '0612345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Drinks Supplier B',
                'contact_number' => '0687654321',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Bakery C',
                'contact_number' => '0611122233',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Vegetable D',
                'contact_number' => '0622233344',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Meat Supplier E',
                'contact_number' => '0633344455',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Fruit Supplier F',
                'contact_number' => '0644455566',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
