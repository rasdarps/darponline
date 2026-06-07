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
            array('id' => 1, 'name' => 'Rasdarps Enterprise', 'address' => 'P O Box ks 4508, Adum, Kumasi', 
            'phone' =>'0242222874', 'email'=>'company@email.com',
            'created_at' => now()),
           

        );

        foreach ($company as $comp) {
            Company::updateOrCreate(
                ['id' => $comp['id']],
                [
                    'name' => $comp['name'],
                    'address' => $comp['address'],
                    'phone' => $comp['phone'],
                    'email' => $comp['email'],
                    'created_at' => now(),
                    'created_by' => 1,
                ]
            );
        }
    }
}
