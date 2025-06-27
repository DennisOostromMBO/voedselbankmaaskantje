<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'supplier_id' => 1,
                'delivery_date' => now(),
                'product_name' => 'Brood',
                'number' => 100,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 2,
                'delivery_date' => now(),
                'product_name' => 'Melk',
                'number' => 200,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 3,
                'delivery_date' => now(),
                'product_name' => 'Appels',
                'number' => 150,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 4,
                'delivery_date' => now(),
                'product_name' => 'Wortels',
                'number' => 120,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 5,
                'delivery_date' => now(),
                'product_name' => 'Varkensvlees',
                'number' => 80,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 6,
                'delivery_date' => now(),
                'product_name' => 'Bananen',
                'number' => 90,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 7,
                'delivery_date' => now(),
                'product_name' => 'Zalm',
                'number' => 60,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 8,
                'delivery_date' => now(),
                'product_name' => 'Diepvriespizza',
                'number' => 70,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 9,
                'delivery_date' => now(),
                'product_name' => 'Bonen in blik',
                'number' => 110,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
