<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectedParcelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('collected_parcels')->insert([
            [
                'volunteer_id' => 1,
                'customer_id' => 1,
                'food_parcel_id' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'volunteer_id' => 2,
                'customer_id' => 2,
                'food_parcel_id' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'volunteer_id' => 3,
                'customer_id' => 3,
                'food_parcel_id' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
