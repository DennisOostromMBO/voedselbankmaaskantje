<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stocks')->insert([
            [
                'product_category_id' => 1,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'kg',
                'quantity_in_stock' => 100,
                'quantity_delivered' => 20,
                'quantity_supplied' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 2,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'kg',
                'quantity_in_stock' => 80,
                'quantity_delivered' => 15,
                'quantity_supplied' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 3,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'ltr',
                'quantity_in_stock' => 60,
                'quantity_delivered' => 10,
                'quantity_supplied' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 4,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'stuks',
                'quantity_in_stock' => 50,
                'quantity_delivered' => 5,
                'quantity_supplied' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 5,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'kg',
                'quantity_in_stock' => 70,
                'quantity_delivered' => 12,
                'quantity_supplied' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 6,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'kg',
                'quantity_in_stock' => 90,
                'quantity_delivered' => 18,
                'quantity_supplied' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 7,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'ltr',
                'quantity_in_stock' => 40,
                'quantity_delivered' => 7,
                'quantity_supplied' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 8,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'stuks',
                'quantity_in_stock' => 30,
                'quantity_delivered' => 4,
                'quantity_supplied' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 9,
                'is_active' => true,
                'received_date' => now(),
                'delivered_date' => now()->addDays(7),
                'unit' => 'kg',
                'quantity_in_stock' => 55,
                'quantity_delivered' => 11,
                'quantity_supplied' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
