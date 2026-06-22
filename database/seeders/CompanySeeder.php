<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //
         $company = array(
            array('id' => 1, 'name' => 'Rasdarps Enterprise', 'address' => 'Kotobabi Down, Accra, Ghana', 
            'phone' =>'0242222874', 'tin_number' => 'GHA-000000000-0', 'email'=>'rasdarps@email.com',
            'created_at' => now()),
           

        );

        foreach ($company as $comp) {
            Company::updateOrCreate(
                ['id' => $comp['id']],
                [
                    'name' => $comp['name'],
                    'address' => $comp['address'],
                    'phone' => $comp['phone'],
                    'tin_number' => $comp['tin_number'],
                    'email' => $comp['email'],
                    'created_at' => now(),
                    'created_by' => 1,
                ]
            );
        }
    }
}
