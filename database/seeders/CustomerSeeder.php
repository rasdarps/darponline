<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = array(
            array(
                'id' => 1,
                'name' => 'cash_sales',
                'mobile_no' => '0000000000',
                'email' => 'cash_sales@email.com',
                'address' => 'PMB Accra Ghana',
                'created_by' => 1,
                'created_at' => now(),
            ),
        );

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['id' => $customer['id']],
                [
                    'name' => $customer['name'],
                    'mobile_no' => $customer['mobile_no'],
                    'email' => $customer['email'],
                    'address' => $customer['address'],
                    'created_by' => $customer['created_by'],
                    'created_at' => now(),
                ]
            );
        }
    }
}
