<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantPickupPointSeeder extends Seeder
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

        $restaurants = Restaurant::with(['bartenders'])->get();

        $pickupPointArr = [
            [
                'name'  => 'Front Bar'
            ],
            [
                'name'  => 'Back Bar'
            ],
        ];

        if( $restaurants->count() )
        {
            foreach( $restaurants as $restaurant )
            {
                if( $restaurant->id == 4 )
                {
                    if( $restaurant->bartenders->count() )
                    {
                        foreach( $restaurant->bartenders as $bartender )
                        {
                            if( !empty($pickupPointArr) )
                            {
                                foreach( $pickupPointArr as $pickupPoint )
                                {
                                    // check if pickup point assigned to same bartender
                                    $checkExist = $restaurant->pickup_points()->where('user_id', $bartender->id)->first();

                                    if( !isset( $checkExist->id ) )
                                    {
                                        $pickupPoint['user_id'] = $bartender->id;
                                        $restaurant->pickup_points()->create($pickupPoint);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
