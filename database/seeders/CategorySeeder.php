<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

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
                'slug' => 'laptops',
                'description' => 'Portable computers including notebooks, ultrabooks, gaming laptops, and business laptops.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Phones',
                'slug' => 'phones',
                'description' => 'Smartphones, mobile phones, and related communication devices from various brands.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Stationary',
                'slug' => 'stationary',
                'description' => 'Office and school supplies such as pens, pencils, notebooks, paper, and folders.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Drinks',
                'slug' => 'drinks',
                'description' => 'Beverages including soft drinks, juices, bottled water, energy drinks, and more.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Packaged foods, groceries, snacks, and other edible consumer products.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Men’s, women’s, and children’s apparel including shirts, trousers, dresses, and uniforms.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Footwear',
                'slug' => 'footwear',
                'description' => 'Shoes, sandals, slippers, boots, and other footwear products.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Fashion and electronic accessories such as bags, belts, watches, chargers, and headphones.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'description' => 'Household appliances including refrigerators, microwaves, blenders, fans, and televisions.',
                'created_by' => 1,
                'created_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'created_by' => $category['created_by'],
                    'is_active' => 1,
                    'show_on_homepage' => 1,
                    'sort_order' => 0,
                    'created_at' => now(),
                ]
            );
        }
    }
}
