<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $countryArr = [
            [
                'name'          => 'India',
                'code'          => 'INR',
                'country_code'  => '+91',
                'symbol'        => '₹',
            ],
            [
                'name'  => 'United States Of America',
                'code'  => 'USD',
                'country_code'  => '+1',
                'symbol'        => '$',
            ],
            [
                'name'  => 'Australia',
                'code'  => 'AUD',
                'country_code'  => '+61',
                'symbol'        => '$',
            ]
        ];

        if( !empty( $countryArr ) )
        {
            foreach( $countryArr as $country )
            {
                Country::create($country);
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
