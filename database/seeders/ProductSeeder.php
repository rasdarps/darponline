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
                'name' => 'HP Pavilion 15 Laptop',
                'slug ' => 'hp-pavilion-15-laptop',
                'barcode' => 'HP0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'brand_id' => 1,
                'category_id' => 1,
                'stock_level' => 0,
                'description' => 'HP Pavilion 15 Laptop with Intel Core i5, 8GB RAM, 512GB SSD, Windows 10',
                'min_order_quantity' => 1,
                'is_featured' => true,
                'is_on_sale' => false,
                'is_new' => true,
                'is_online' => true,
                'tags' => 'laptop, hp, pavilion, intel, core i5, 8GB RAM, 512GB SSD, Windows 10',
                'seo_title' => 'HP Pavilion 15 Laptop - Intel Core i5, 8GB RAM, 512GB SSD, Windows 10',
                'seo_description' => 'Buy the HP Pavilion 15 Laptop with Intel Core i5, 8GB RAM, 512GB SSD, and Windows 10. Perfect for work and play.',
                'created_at' => now(),
                'created_by' => 1,
            ],
            [
                'id' => 2,
                'name' => 'iPhone 13 Pro',
                'slug ' => 'iphone-13-pro',
                'barcode' => 'IP0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'brand_id' => 2,
                'category_id' => 2,
                'stock_level' => 5,
                'description' => 'Apple iPhone 13 Pro with A15 Bionic chip, 6GB RAM, and advanced camera system.',
                'min_order_quantity' => 1,
                'is_featured' => true,
                'is_on_sale' => false,
                'is_new' => true,
                'is_online' => true,
                'tags' => 'iphone, apple, iphone 13 pro, a15 bionic, 6GB RAM, camera',
                'seo_title' => 'Apple iPhone 13 Pro - A15 Bionic, 6GB RAM, Advanced Camera',
                'seo_description' => 'Get the Apple iPhone 13 Pro with A15 Bionic chip, 6GB RAM, and an advanced camera system for stunning photos and videos.',
                'created_at' => now(),
                'created_by' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'slug ' => 'lenovo-thinkpad-x1-carbon',
                'barcode' => 'LN0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'brand_id' => 3,
                'category_id' => 1,
                'stock_level' => 10,
                'description' => 'Lenovo ThinkPad X1 Carbon with Intel Core i7, 16GB RAM, 1TB SSD, Windows 10 Pro.',
                'min_order_quantity' => 1,
                'is_featured' => true,
                'is_on_sale' => false,
                'is_new' => true,
                'is_online' => true,
                'tags' => 'lenovo, thinkpad, x1 carbon, intel core i7, 16GB RAM, 1TB SSD, windows 10 pro',
                'seo_title' => 'Lenovo ThinkPad X1 Carbon - Intel Core i7, 16GB RAM, 1TB SSD',
                'seo_description' => 'Experience the Lenovo ThinkPad X1 Carbon with Intel Core i7, 16GB RAM, and a spacious 1TB SSD. Ideal for professionals on the go.',
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
