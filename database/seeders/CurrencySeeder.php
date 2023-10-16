<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
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

        $currencyArr = [
            [
                'name'  => 'Indian Ruppee',
                'code'  => 'INR'
            ],
            [
                'name'  => 'US Dollar',
                'code'  => 'USD'
            ],
            [
                'name'  => 'Australlian Dollar',
                'code'  => 'AUD'
            ]
        ];

        if( !empty( $currencyArr ) )
        {
            foreach( $currencyArr as $currency )
            {
                Currency::create($currency);
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
