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
                'name'              => 'Magnet House',
                'latitude'          => '-27.462734875149664',
                'longitude'         => '153.0303873486703',
                'address'           => 'Riverside Centre, 123 Eagle St, Brisbane City QLD 4000, Australia',
                'phone'             => '61732291200',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'The Court Hotel',
                'latitude'          => '-34.91748061090115',
                'longitude'         => '138.59970174021134',
                'address'           => '2 King William St, Adelaide SA 5000, Australia',
                'phone'             => '61882125511',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Metro City',
                'latitude'          => '-34.918293353901326',
                'longitude'         => '138.60736934023438',
                'address'           => '205 Rundle St, Adelaide SA 5000, Australia',
                'phone'             => '61883100210',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Butterfly 73',
                'latitude'          => '-33.8789136',
                'longitude'         => '151.145561',
                'address'           => '4/256 Crown St, Darlinghurst NSW 2010, Australia',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Employees Only Bar',
                'latitude'          => '-33.8677238',
                'longitude'         => '151.1360824',
                'address'           => '9a Barrack St, Sydney NSW 2000, Australia',
                'phone'             => '61280847490',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => 'Employees Only Bar',
                'latitude'          => '-33.8677238',
                'longitude'         => '151.1360824',
                'address'           => '9a Barrack St, Sydney NSW 2000, Australia',
                'phone'             => '61280847490',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Grandma's Bar",
                'latitude'          => '-33.872084',
                'longitude'         => '151.1354003',
                'address'           => 'Basement/275 Clarence St, Sydney NSW 2000, Australia',
                'phone'             => '61292643004',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Boilermaker House",
                'latitude'          => '-37.8113429',
                'longitude'         => '144.8962351',
                'address'           => '209-211 Lonsdale St, Melbourne VIC 3000, Australia',
                'phone'             => '61383939367',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Earl's Juke Joint",
                'latitude'          => '-33.8999802',
                'longitude'         => '151.1077377',
                'address'           => '407 King St, Newtown NSW 2042, Australia',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Cantina OK!",
                'latitude'          => '-33.86435432630634',
                'longitude'         => '151.2047850911538',
                'address'           => 'Council Pl, Sydney NSW 2000, Australia',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Cabana Bar",
                'latitude'          => '-33.86350950341852',
                'longitude'         => '151.2109746276853',
                'address'           => '25 Martin Pl, Sydney NSW 2000, Australia',
                'phone'             => '61292156000',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Establishment Bar",
                'latitude'          => '-33.859072608692266',
                'longitude'         => '151.20828375928136',
                'address'           => 'Ground Floor, 252 George St, Sydney NSW 2000, Australia',
                'phone'             => '61291147310',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Old Mates Place",
                'latitude'          => '-33.863397796643994',
                'longitude'         => '151.20480739113526',
                'address'           => 'level 4/199 Clarence St, Sydney NSW 2000, Australia',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "Molly",
                'latitude'          => '-35.274696096305654',
                'longitude'         => '149.12706706250006',
                'address'           => 'Wooden Door, Odgers Ln, Canberra ACT 2601, Australia',
                'phone'             => '61261798973',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
            [
                'name'              => "The Lobo",
                'latitude'          => '-33.86584668571258',
                'longitude'         => '151.20568435944492',
                'address'           => 'basement lot 1/209 Clarence St, Sydney NSW 2000, Australia',
                'specialisation'    => 'Bar',
                'currency_id'       => 3,
                'country_id'        => 3
            ],
        ];

        if( !empty( $restaurantArr ) )
        {
            $counter = 1;
            foreach( $restaurantArr as $restaurant )
            {
                $newRestaurant = Restaurant::create($restaurant);

                // logic to upload photo of restaurant
                $newRestaurant->attachment()->create([
                    'original_name' => $counter.'.jpg',
                    'stored_name'   => $counter.'.jpg'
                ]);

                // restaurant owner
                $newRestaurant->owners()->attach(2);

                // TODO: Restaurant bartenders

                $counter++;
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
