<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
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
                'name'              => 'Delta Goodrem',
                'latitude'          => '31.9568546',
                'longitude'         => '115.85379',
                'street1'           => 'Parenting Room/21',
                'street2'           => 'Mounts Bay Rd',
                'city'              => 'Perth',
                'state'             => 'WA',
                'postcode'          => '6000',
                'phone'             => '1111111111',
                'specialisation'    => '',
                'currency_id'       => 3,
                'country_id'        => 3,
                'type'              => 2,
                'start_date'        => '2023-10-07 20:00:00',
                'end_date'          => '2023-10-07 23:00:00'
            ],
            [
                'name'              => 'GHOST',
                'latitude'          => '-27.478958813523203',
                'longitude'         => '153.02999275581843',
                'street1'           => '59 Gardens Point Rd',
                'city'              => 'Brisbane City',
                'state'             => 'QLD',
                'postcode'          => '4000',
                'phone'             => '2222222222',
                'specialisation'    => '',
                'currency_id'       => 3,
                'country_id'        => 3,
                'type'              => 2,
                'start_date'        => '2023-10-07 20:00:00',
                'end_date'          => '2023-10-07 22:00:00'
            ],
            [
                'name'              => 'Waterparks',
                'latitude'          => '-37.8165928978529',
                'longitude'         => '144.96924350607415',
                'street1'           => '154 Flinders St',
                'city'              => 'Melbourne',
                'state'             => 'VIC',
                'postcode'          => '3000',
                'phone'             => '3333333333',
                'specialisation'    => '',
                'currency_id'       => 3,
                'country_id'        => 3,
                'type'              => 2,
                'start_date'        => '2023-10-08 18:00:00',
                'end_date'          => '2023-10-08 23:59:59'
            ],
            [
                'name'              => 'Pendulum',
                'latitude'          => '-31.94837027420399',
                'longitude'         => '115.85214218650894',
                'street1'           => '700 Wellington St',
                'city'              => 'Perth',
                'state'             => 'WA',
                'postcode'          => '6000',
                'phone'             => '4444444444',
                'specialisation'    => '',
                'currency_id'       => 3,
                'country_id'        => 3,
                'type'              => 2,
                'start_date'        => '2023-10-08 18:30:00',
                'end_date'          => '2023-10-08 23:00:00'
            ],
            [
                'name'              => 'Tim Minchin - An Unfunny Evening with Tim Minchin and his Piano',
                'latitude'          => '-37.81007061685391',
                'longitude'         => '144.97001297116364',
                'street1'           => '240 Exhibition St',
                'city'              => 'Melbourne',
                'state'             => 'VIC',
                'postcode'          => '3000',
                'phone'             => '5555555555',
                'specialisation'    => '',
                'currency_id'       => 3,
                'country_id'        => 3,
                'type'              => 2,
                'start_date'        => '2023-10-12 18:00:00',
                'end_date'          => '2023-10-29 23:59:59'
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
                // if( $newRestaurant->id == 4 )
                // {
                //     // Logic create restaurant user
                //     $newRestaurant->bartenders()->attach([4,5]);
                // }

                $counter++;
                $owner++;
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
