<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Dairy', 'Bakery', 'Vegetables', 'Fruits', 'Beverages', 'Snacks', 'Meat', 'Frozen'];

        for ($i = 1; $i <= 100; $i++) {
            $category = $categories[array_rand($categories)];
            
            $productId = DB::table('products')->insertGetId([
                'name'       => "Product $i",
                'category'   => $category,
                'price'      => rand(50, 1500) / 10 * 10,
                'image_url'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('inventory')->insert([
                'product_id' => $productId,
                'quantity'   => rand(0, 100),
                'updated_at' => now(),
            ]);
        }
    }
}
