<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $products = [
            [
                'id' => 1,
                'name' => 'Rice Cooker 2litres',
                'slug ' => 'rice-cooker-2litres',
                'barcode' => 'RC0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'brand_id' => 1,
                'category_id' => 1,
                'stock_level' => 0,
                'is_active' => true,
                'description' => 'Rice Cooker 2litres with multiple cooking functions and a sleek design.',
                'min_order_quantity' => 1,
                'is_featured' => true,
                'is_on_sale' => false,
                'is_new' => true,
                'is_online' => true,
                'tags' => 'rice cooker, kitchen appliance, 2litres, cooking',
                'seo_title' => 'Rice Cooker 2litres - Multiple Cooking Functions',
                'seo_description' => 'Buy the Rice Cooker 2litres with multiple cooking functions and a sleek design. Perfect for your kitchen.',
                'created_at' => now(),
                'created_by' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Stanley Cup 1.5litres',
                'slug ' => 'stanley-cup-1-5litres',
                'barcode' => 'SC0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'brand_id' => 2,
                'category_id' => 2,
                'stock_level' => 5,
                'is_active' => true,
                'description' => 'Stanley Cup 1.5litres with durable construction and excellent insulation.',
                'min_order_quantity' => 1,
                'is_featured' => true,
                'is_on_sale' => false,
                'is_new' => true,
                'is_online' => true,
                'tags' => 'stanley cup, 1.5litres, durable, insulated',
                'seo_title' => 'Stanley Cup 1.5litres - Durable and Insulated',
                'seo_description' => 'Get the Stanley Cup 1.5litres with durable construction and excellent insulation. Perfect for keeping your beverages hot or cold.',
                'created_at' => now(),
                'created_by' => 1,
            ],
            [
                'id' => 3,
                'name' => '4 in 1 Blender',
                'slug ' => '4-in-1-blender',
                'barcode' => 'BL0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'brand_id' => 3,
                'category_id' => 1,
                'stock_level' => 10,
                'is_active' => true,
                'description' => '4 in 1 Blender with multiple functions for blending, chopping, and mixing.',
                'min_order_quantity' => 1,
                'is_featured' => true,
                'is_on_sale' => false,
                'is_new' => true,
                'is_online' => true,
                'tags' => 'blender, 4 in 1, kitchen appliance, blending, chopping, mixing',
                'seo_title' => '4 in 1 Blender - Multiple Functions for Blending, Chopping, and Mixing',
                'seo_description' => 'Get the 4 in 1 Blender with multiple functions for blending, chopping, and mixing. Perfect for your kitchen.',
                'created_at' => now(),
                'created_by' => 1,
            ],
         ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['id' => $product['id']],
                [
                    'name' => $product['name'],
                    'slug' => $product['slug '],
                    'barcode' => $product['barcode'],
                    'quantity' => $product['quantity'],
                    'unit_id' => $product['unit_id'],
                    'brand_id' => $product['brand_id'],
                    'category_id' => $product['category_id'],
                    'stock_level' => $product['stock_level'],
                    'is_active' => $product['is_active'],
                    'description' => $product['description'],
                    'min_order_quantity' => $product['min_order_quantity'],
                    'is_featured' => $product['is_featured'],
                    'is_on_sale' => $product['is_on_sale'],
                    'is_new' => $product['is_new'],
                    'is_online' => $product['is_online'],
                    'tags' => $product['tags'],
                    'seo_title' => $product['seo_title'],
                    'seo_description' => $product['seo_description'],
                    'created_by' => $product['created_by'],
                    'created_at' => now(),
                ]
            );
        }
    }
}
