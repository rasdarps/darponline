<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            [
                'id' => 1,
                'name' => 'Laptops',
                'description' => 'Portable computers including notebooks, ultrabooks, gaming laptops, and business laptops.',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Phones',
                'description' => 'Smartphones, mobile phones, and related communication devices from various brands.',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Stationary',
                'slug' => 'stationary',
                'description' => 'Office and school supplies such as pens, pencils, notebooks, paper, and folders.',
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Drinks',
                'description' => 'Beverages including soft drinks, juices, bottled water, energy drinks, and more.',
                'created_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Food',
                'description' => 'Packaged foods, groceries, snacks, and other edible consumer products.',
                'created_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Clothing',
                'description' => 'Men’s, women’s, and children’s apparel including shirts, trousers, dresses, and uniforms.',
                'created_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Footwear',
                'description' => 'Shoes, sandals, slippers, boots, and other footwear products.',
                'created_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Accessories',
                'description' => 'Fashion and electronic accessories such as bags, belts, watches, chargers, and headphones.',
                'created_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Home Appliances',
                'description' => 'Household appliances including refrigerators, microwaves, blenders, fans, and televisions.',
                'created_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'created_at' => now(),
                    'created_by' => 1,
                ]
            );
        }
    }
}
