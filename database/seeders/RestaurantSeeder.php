<?php

namespace Database\Seeders;

use App\Models\Restaurant;
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
                'name'              => 'The Bird',
                'latitude'          => '-27.462734875149664',
                'longitude'         => '153.0303873486703',
                'street1'           => 'Riverside Centre',
                'street2'           => '123 Eagle St',
                'city'              => 'Brisbane City',
                'state'             => 'QLD',
                'postcode'          => '4000',
                'phone'             => '61732291200',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'The Court Hotel',
                'latitude'          => '-34.91748061090115',
                'longitude'         => '138.59970174021134',
                'street1'           => '2 King William St',
                'city'              => 'Adelaide',
                'state'             => 'SA',
                'postcode'          => '5000',
                'phone'             => '61882125511',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Metro City',
                'latitude'          => '-34.918293353901326',
                'longitude'         => '138.60736934023438',
                'street1'           => '205 Rundle St',
                'city'              => 'Adelaide',
                'state'             => 'SA',
                'postcode'          => '5000',
                'phone'             => '61883100210',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Butterfly 73',
                'latitude'          => '-33.8789136',
                'longitude'         => '151.145561',
                'street1'           => '4/256 Crown St',
                'city'              => 'Darlinghurst',
                'state'             => 'NSW',
                'postcode'          => '2010',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Employees Only Bar',
                'latitude'          => '-33.8677238',
                'longitude'         => '151.1360824',
                'street1'           => '9a Barrack St',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'phone'             => '61280847490',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Royal Lounge',
                'latitude'          => '-33.8678589',
                'longitude'         => '151.1159335',
                'street1'           => 'Enmore',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'phone'             => '61280847490',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Grandma's Bar",
                'latitude'          => '-33.872084',
                'longitude'         => '151.1354003',
                'street1'           => 'Basement/275 Clarence St',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'phone'             => '61292643004',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Boilermaker House",
                'latitude'          => '-37.8113429',
                'longitude'         => '144.8962351',
                'street1'           => '209-211 Lonsdale St',
                'city'              => 'Melbourne',
                'state'             => 'VIC',
                'postcode'          => '3000',
                'phone'             => '61383939367',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Earl's Juke Joint",
                'latitude'          => '-33.8999802',
                'longitude'         => '151.1077377',
                'street1'           => '407 King St',
                'city'              => 'Newtown',
                'state'             => 'NSW',
                'postcode'          => '2042',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Cantina OK!",
                'latitude'          => '-33.86435432630634',
                'longitude'         => '151.2047850911538',
                'street1'           => 'Council Pl',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Cabana Bar",
                'latitude'          => '-33.86350950341852',
                'longitude'         => '151.2109746276853',
                'street1'           => '25 Martin Pl',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'phone'             => '61292156000',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Establishment Bar",
                'latitude'          => '-33.859072608692266',
                'longitude'         => '151.20828375928136',
                'street1'           => 'Ground Floor',
                'street2'           => '252 George St',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'phone'             => '61291147310',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Old Mates Place",
                'latitude'          => '-33.863397796643994',
                'longitude'         => '151.20480739113526',
                'street1'           => 'level 4/199 Clarence St',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Molly",
                'latitude'          => '-35.274696096305654',
                'longitude'         => '149.12706706250006',
                'street1'           => 'Wooden Door',
                'street2'           => 'Odgers Ln',
                'city'              => 'Canberra',
                'state'             => 'ACT',
                'postcode'          => '2601',
                'phone'             => '61261798973',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "The Lobo",
                'latitude'          => '-33.86584668571258',
                'longitude'         => '151.20568435944492',
                'street1'           => 'basement lot 1/209 Clarence St',
                'city'              => 'Sydney',
                'state'             => 'NSW',
                'postcode'          => '2000',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
        ];

        if( !empty( $restaurantArr ) )
        {
            $counter = 1;
            $owner   = 2;
            foreach( $restaurantArr as $restaurant )
            {
                $newRestaurant = Restaurant::create($restaurant);

                // logic to upload photo of restaurant
                $newRestaurant->attachment()->create([
                    'original_name' => $counter.'.jpg',
                    'stored_name'   => $counter.'.jpg'
                ]);

                // restaurant owner
                $newRestaurant->owners()->attach($owner);

                // Restaurant bartenders
                if( $newRestaurant->id == 4 )
                {
                    // Logic create restaurant user
                    $newRestaurant->bartenders()->attach([4,5]);
                }

                $counter++;
                $owner++;
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
