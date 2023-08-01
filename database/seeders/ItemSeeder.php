<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
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

        $itemArr = [
            [
                'name'          => 'Brandy & Weinbrand',
                'item_type_id'  => 1,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'1.jpg'
            ],
            [
                'name'          => 'Gin',
                'item_type_id'  => 1,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'1.jpg'
            ],
            [
                'name'          => 'Liquers',
                'item_type_id'  => 1,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'1.jpg'
            ],
            [
                'name'          => 'Rum',
                'item_type_id'  => 1,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'1.jpg'
            ],
            [
                'name'          => 'Vodka',
                'item_type_id'  => 1,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'1.jpg'
            ],
            [
                'name'          => 'Old Fashioned',
                'item_type_id'  => 2,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'2.jpg'
            ],
            [
                'name'          => 'Negroni',
                'item_type_id'  => 2,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'2.jpg'
            ],
            [
                'name'          => 'Whisky Sour',
                'item_type_id'  => 2,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'2.jpg'
            ],
            [
                'name'          => 'Dry Martini',
                'item_type_id'  => 2,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'3.jpg'
            ],
            [
                'name'          => 'Manhattan',
                'item_type_id'  => 2,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'2.jpg'
            ],
            [
                'name'          => 'Lager',
                'item_type_id'  => 3,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'3.jpg'
            ],
            [
                'name'          => 'Ale',
                'item_type_id'  => 3,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'3.jpg'
            ],
            [
                'name'          => 'Stout',
                'item_type_id'  => 3,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'3.jpg'
            ],
            [
                'name'          => 'Porter',
                'item_type_id'  => 3,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'3.jpg'
            ],
            [
                'name'          => 'Brown Ale',
                'item_type_id'  => 3,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'3.jpg'
            ],
            [
                'name'          => 'Red Wine',
                'item_type_id'  => 4,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'4.jpg'
            ],
            [
                'name'          => 'White Wine',
                'item_type_id'  => 4,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'4.jpg'
            ],
            [
                'name'          => 'Rose Wine',
                'item_type_id'  => 4,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'4.jpg'
            ],
            [
                'name'          => 'Sparkling Wine',
                'item_type_id'  => 4,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'4.jpg'
            ],
            [
                'name'          => 'Dessert Wine',
                'item_type_id'  => 4,
                'type'          => Item::ITEM,
                'is_variable'   => 1,
                'price'         => 10,
                'image'         =>'4.jpg'
            ]
        ];

        if( !empty( $itemArr ) )
        {
            foreach( $itemArr as $item )
            {
                Item::create($item);
            }
        }

        // enable foreign key checks for this connection after running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
