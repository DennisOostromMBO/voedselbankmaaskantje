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
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'kg',
                'aantalOpVoorad' => 100,
                'aantalUigegeven' => 20,
                'aantalBijgeleverd' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 2,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'kg',
                'aantalOpVoorad' => 80,
                'aantalUigegeven' => 15,
                'aantalBijgeleverd' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 3,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'ltr',
                'aantalOpVoorad' => 60,
                'aantalUigegeven' => 10,
                'aantalBijgeleverd' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 4,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'stuks',
                'aantalOpVoorad' => 50,
                'aantalUigegeven' => 5,
                'aantalBijgeleverd' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 5,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'kg',
                'aantalOpVoorad' => 70,
                'aantalUigegeven' => 12,
                'aantalBijgeleverd' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 6,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'kg',
                'aantalOpVoorad' => 90,
                'aantalUigegeven' => 18,
                'aantalBijgeleverd' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 7,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'ltr',
                'aantalOpVoorad' => 40,
                'aantalUigegeven' => 7,
                'aantalBijgeleverd' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 8,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'stuks',
                'aantalOpVoorad' => 30,
                'aantalUigegeven' => 4,
                'aantalBijgeleverd' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_category_id' => 9,
                'is_active' => true,
                'ontvangdatum' => now(),
                'uigeleverddatum' => now()->addDays(7),
                'eenheid' => 'kg',
                'aantalOpVoorad' => 55,
                'aantalUigegeven' => 11,
                'aantalBijgeleverd' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
