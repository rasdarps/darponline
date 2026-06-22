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
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Kitchen Ware',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Bathroom Ware',
                'created_by' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Decorations',
                'created_by' => 1,
                'created_at' => now(),
            ],
           
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                    'created_by' => $category['created_by'],
                    'created_at' => now(),
                ]
            );
        }
    }
}
