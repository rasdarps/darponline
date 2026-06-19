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
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'description' => 'Household appliances including refrigerators, microwaves, blenders, fans, and televisions.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Kitchen Ware',
                'slug' => 'kitchen-ware',
                'description' => 'Kitchen utensils, cookware, and other kitchen-related items.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Bathroom Ware',
                'slug' => 'bathroom-ware',
                'description' => 'Bathroom essentials including towels, shower curtains, bath mats, and toiletries.',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Decorations',
                'slug' => 'decorations',
                'description' => 'Various types of decorations including vases, wall art, candles, and other decorative items.',
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
