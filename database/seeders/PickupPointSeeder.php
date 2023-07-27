<?php

namespace Database\Seeders;

use App\Models\PickupPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PickupPointSeeder extends Seeder
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

        $pickupPointArr = [
            [
                'name'  => 'Front Bar'
            ],
            [
                'name'  => 'Back Bar'
            ],
        ];

        if( !empty($pickupPointArr) )
        {
            foreach( $pickupPointArr as $pickupPoint )
            {
                PickupPoint::create($pickupPoint);
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
