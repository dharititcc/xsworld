<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
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

        $restaurantArr = [
            [
                'name'              => 'Blackbird Bar',
                'latitude'          => '-27.462734875149664',
                'longitude'         => '153.0303873486703',
                'address'           => 'Riverside Centre, 123 Eagle St, Brisbane City QLD 4000, Australia',
                'phone'             => '61732291200',
                'specialisation'    => 'Bar'
            ],
            [
                'name'              => '2KW Bar & Restaurant',
                'latitude'          => '-34.91748061090115',
                'longitude'         => '138.59970174021134',
                'address'           => '2 King William St, Adelaide SA 5000, Australia',
                'phone'             => '61882125511',
                'specialisation'    => 'Bar'
            ],
            [
                'name'              => 'The Austral',
                'latitude'          => '-34.918293353901326',
                'longitude'         => '138.60736934023438',
                'address'           => '205 Rundle St, Adelaide SA 5000, Australia',
                'phone'             => '61883100210',
                'specialisation'    => 'Bar'
            ]
        ];

        if( !empty( $restaurantArr ) )
        {
            foreach( $restaurantArr as $restaurant )
            {
                $newRestaurant = Restaurant::create($restaurant);

                //TODO: logic to upload photo of restaurant

                // Logic related to link item types
                $newRestaurant->item_types()->attach([1,2,3,4]);

                // TODO: Logic related to link items

                $newRestaurant->user()->attach(2);
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
