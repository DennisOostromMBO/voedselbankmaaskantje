<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'supplier_name' => 'Food Supplier A',
                'contact_number' => '0612345678',
                'is_active' => true,
                'upcoming_delivery_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Drinks Supplier B',
                'contact_number' => '0687654321',
                'is_active' => true,
                'upcoming_delivery_at' => Carbon::now()->addDays(2)->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Bakery C',
                'contact_number' => '0611122233',
                'is_active' => true,
                'upcoming_delivery_at' => Carbon::now()->addDays(5)->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Vegetable D',
                'contact_number' => '0622233344',
                'is_active' => true,
                'upcoming_delivery_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Meat Supplier E',
                'contact_number' => '0633344455',
                'is_active' => true,
                'upcoming_delivery_at' => Carbon::now()->addDays(1)->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Fruit Supplier F',
                'contact_number' => '0644455566',
                'is_active' => true,
                'upcoming_delivery_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Fish Supplier G',
                'contact_number' => '0655566677',
                'is_active' => true,
                'upcoming_delivery_at' => Carbon::now()->addDays(3)->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Frozen Supplier H',
                'contact_number' => '0666677788',
                'is_active' => true,
                'upcoming_delivery_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'Canned Supplier I',
                'contact_number' => '0677788899',
                'is_active' => true,
                'upcoming_delivery_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
