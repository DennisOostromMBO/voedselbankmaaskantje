<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_categories')->insert([
            [
                'product_id' => 1,
                'category_name' => 'Aardappels, Groenten en Fruit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'category_name' => 'Kaas en vleeswaren',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'category_name' => 'Zuivel, plantaardig en eieren',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 4,
                'category_name' => 'Bakkerij en banket',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 5,
                'category_name' => 'Frisdank, sappen, koffie en thee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 6,
                'category_name' => 'Pasta, rijst en wereldkeuken',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 7,
                'category_name' => 'Soepen, sausen, kruiden en olie',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 8,
                'category_name' => 'Snoep, koek, chips en chocolade',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 9,
                'category_name' => 'Baby, verzorging en hygiëne',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
