<?php

namespace Database\Seeders;

use App\Models\Set;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sets = [
            [
                'id'                    => 1,
                'scenario'              => '1,1,0,0',
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 2,
                'scenario'              => '1,0,1,0',
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString()
            ],
            [
                'id'                    => 3,
                'scenario'              => '0,1,1,0',
                'created_at' 	        => Carbon::now()->toDateTimeString(),
                'updated_at' 	        => Carbon::now()->toDateTimeString()
            ]
        ];

        if( !empty( $sets ) )
        {
            foreach( $sets as $set )
            {
                Set::create($set);
            }
        }
    }
}
