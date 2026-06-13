<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $suppliers = [
            [
                'id' => 1,
                'name' => 'cash_purchase',
                'mobile_no' => '0000000000',
                'email' => 'cash_purchase@email.com',
                'address' => 'PMB Accra Ghana',
                'city' => 'Accra',
                'type' => 'Manufacturer',
                'created_at' => now(),
                'created_by' => 1,
            ],
            
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['id' => $supplier['id']],
                [
                    'name' => $supplier['name'],
                    'mobile_no' => $supplier['mobile_no'],
                    'email' => $supplier['email'],
                    'address' => $supplier['address'],
                    'city' => $supplier['city'],
                    'type' => $supplier['type'],
                    'created_by' => $supplier['created_by'],
                    'created_at' => now(),
                ]
            );
        }
    }
}
