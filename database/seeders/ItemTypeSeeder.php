<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypeSeeder extends Seeder
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

        $itemTypeArr = [
            [
                'name' => 'Spirits'
            ],
            [
                'name' => 'Cocktails'
            ],
            [
                'name' => 'Beers'
            ],
            [
                'name' => 'Wines'
            ]
        ];

        if( !empty( $itemTypeArr ) )
        {
            foreach( $itemTypeArr as $itemType )
            {
                $newItemType = ItemType::create($itemType);

                // logic to upload photo of item type
                $newItemType->attachment()->create([
                    'original_name' => strtolower($itemType['name']).'.jpg',
                    'stored_name'   => strtolower($itemType['name']).'.jpg'
                ]);
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
