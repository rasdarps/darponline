<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Database\Seeders\Concerns\SkipsUnchangedUpserts;

class CompanySeeder extends Seeder
{
    use SkipsUnchangedUpserts;

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
            ),
           

        );

        foreach ($company as $comp) {
            $this->upsertOnlyWhenChanged(Company::class, ['id' => $comp['id']], [
                'name' => $comp['name'],
                'address' => $comp['address'],
                'phone' => $comp['phone'],
                'tin_number' => $comp['tin_number'],
                'email' => $comp['email'],
                'created_by' => 1,
            ]);
        }
    }
}
