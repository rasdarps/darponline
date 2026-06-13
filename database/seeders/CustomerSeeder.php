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
                'password' => bcrypt('password123'),
                'address' => 'PMB Accra Ghana',
                'first_name' => 'Cash',
                'last_name' => 'Sales',
                'username' => 'cash_sales',
                'created_by' => 1,
                'email_verified_at' => now(),
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
                    'password' => $customer['password'],
                    'address' => $customer['address'],
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'username' => $customer['username'],
                    'created_by' => $customer['created_by'],
                    'email_verified_at' => $customer['email_verified_at'],
                    'created_at' => now(),
                ]
            );
        }
    }
}
