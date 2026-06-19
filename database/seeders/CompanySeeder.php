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
            array('id' => 1, 'name' => 'Factory Price Mall', 'address' => 'Near Tobinco Pharmaceuticals, Tudu-Mall, Accra, Ghana', 
            'phone' =>'0240795803', 'tin_number' => 'GHA-718909812-9', 'email'=>'factorypricemall@email.com',
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
