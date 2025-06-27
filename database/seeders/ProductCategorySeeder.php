<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Get product IDs by name for clarity
        $appels_id = DB::table('products')->where('product_name', 'Appels')->value('id');
        $bananen_id = DB::table('products')->where('product_name', 'Bananen')->value('id');
        $brood_id = DB::table('products')->where('product_name', 'Brood')->value('id');
        $melk_id = DB::table('products')->where('product_name', 'Melk')->value('id');
        $varkensvlees_id = DB::table('products')->where('product_name', 'Varkensvlees')->value('id');
        $wortels_id = DB::table('products')->where('product_name', 'Wortels')->value('id');
        $diepvriespizza_id = DB::table('products')->where('product_name', 'Diepvriespizza')->value('id');
        $bonen_id = DB::table('products')->where('product_name', 'Bonen in blik')->value('id');
        $zalm_id = DB::table('products')->where('product_name', 'Zalm')->value('id');

        DB::table('product_categories')->insert([
            [
                'product_id' => $appels_id,
                'category_name' => 'Aardappels, Groenten en Fruit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $bananen_id,
                'category_name' => 'Aardappels, Groenten en Fruit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $wortels_id,
                'category_name' => 'Aardappels, Groenten en Fruit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $brood_id,
                'category_name' => 'Bakkerij en banket',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $melk_id,
                'category_name' => 'Zuivel, plantaardig en eieren',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $varkensvlees_id,
                'category_name' => 'Kaas en vleeswaren',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $zalm_id,
                'category_name' => 'Kaas en vleeswaren',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $diepvriespizza_id,
                'category_name' => 'Pasta, rijst en wereldkeuken',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $bonen_id,
                'category_name' => 'Soepen, sausen, kruiden en olie',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
