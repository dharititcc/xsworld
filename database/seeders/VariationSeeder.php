<?php

namespace Database\Seeders;

use App\Models\Variation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariationSeeder extends Seeder
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

        $variationArr = [
            [
                'name' => 'Single Shot'
            ],
            [
                'name' => 'Double Shot'
            ],
            [
                'name' => 'Bottle'
            ],
            [
                'name' => 'Glass'
            ],
            [
                'name' => 'Jug'
            ],
            [
                'name' => 'Pint'
            ]
        ];

        if( !empty( $variationArr ) )
        {
            foreach( $variationArr as $variation )
            {
                Variation::create($variation);
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
