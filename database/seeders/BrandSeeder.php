<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $brands = [
            [
                'id' => 1,
                'name' => 'HP',
                'slug' => 'hp',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Iphone',
                'slug' => 'iphone',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Lenovo',
                'slug' => 'lenovo',
                'created_at' => now(),
            ]
         ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['id' => $brand['id']],
                [
                    'name' => $brand['name'],
                    'slug' => $brand['slug'],
                    'created_at' => now(),
                ]
            );
        }
    }
}
