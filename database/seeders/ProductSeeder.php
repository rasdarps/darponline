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
                'barcode' => 'RC0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'category_id' => 1,
                'stock_level' => 0,
                'cost_price' => 0,
                'selling_price' => 0,
                'created_at' => now(),
                'created_by' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Stanley Cup 1.5litres',
                'barcode' => 'SC0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'category_id' => 2,
                'stock_level' => 5,
                'cost_price' => 0,
                'selling_price' => 0,
                'created_at' => now(),
                'created_by' => 1,
            ],
            [
                'id' => 3,
                'name' => '4 in 1 Blender',
                'barcode' => 'BL0000001',
                'quantity' => 0,
                'unit_id' => 1,
                'category_id' => 1,
                'stock_level' => 10,
                'cost_price' => 0,
                'selling_price' => 0,
                'created_at' => now(),
                'created_by' => 1,
            ],
         ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['id' => $product['id']],
                [
                    'name' => $product['name'],
                    'barcode' => $product['barcode'],
                    'quantity' => $product['quantity'],
                    'unit_id' => $product['unit_id'],
                    'category_id' => $product['category_id'],
                    'stock_level' => $product['stock_level'],
                    'cost_price' => $product['cost_price'],
                    'selling_price' => $product['selling_price'],
                    'created_by' => $product['created_by'],
                    'created_at' => now(),
                ]
            );
        }
    }
}
