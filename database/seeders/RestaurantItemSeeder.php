<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantItemSeeder extends Seeder
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

        // get restaurants
        $restaurants = Restaurant::with(['categories', 'categories.children'])->get();

        $champagneItems = [
            [
                'name'                  => 'Veuve Brut',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 35,
                'image'                 => 'veuve-brut.jpg',
                'ingredients'           => 'Pinot Noir. 50 to 55%',
                'country_of_origin'     => 'France',
                'year_of_production'    => '1772',
                'type_of_drink'         => 'Champagne & Sparkling'
            ],
            [
                'name'                  => 'Pommery Brut Royal',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 85,
                'image'                 => 'pommery-brut-royal.jpg',
                'ingredients'           => 'Chardonnay, Pinot Noir and Pinot Meunier grapes',
                'country_of_origin'     => 'France',
                'year_of_production'    => '1836',
                'type_of_drink'         => 'Champagne & Sparkling'
            ]
        ];

        $wineItems = [
            [
                'name'                  => 'Pirate Life',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 85,
                'image'                 => 'pirate-life.jpg',
                'ingredients'           => 'cocoa, roasted cacao powder, and natural chocolate & caramel flavours',
                'country_of_origin'     => 'Adelaide, South Australia',
                'year_of_production'    => '2014',
                'type_of_drink'         => 'wine'
            ],
            [
                'name'                  => 'Rasteau',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 16,
                'image'                 => 'rasteau.jpg',
                'ingredients'           => 'Grenache Noir, Gris and blanc',
                'country_of_origin'     => 'southern Rhône valley',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'wine'
            ]
        ];

        $spiritItems = [
            [
                'name'                  => 'Rum Range',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 85,
                'image'                 => 'rum-range.jpg',
                'ingredients'           => 'raw cane juice, white or brown cane sugar, cane syrup, evaporated cane sugar and cane molasses',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Whisky Range',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 85,
                'image'                 => 'whisky-range.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ]
        ];

        $cocktailItems = [
            [
                'name'                  => 'Kailis Sunset',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 22,
                'image'                 => 'kailis-sunset.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'The Loop Mania',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 1,
                'price'                 => 20,
                'image'                 => 'the-loop-mania.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ]
        ];

        $beersItems = [
            [
                'name'                  => 'Buckle Bunny Sour',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 22,
                'description'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'image'                 => 'buckle-bunny-sour.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => '805 Draft',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 22,
                'description'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'image'                 => '805-draft.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Kailis Sunset',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 22,
                'description'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'image'                 => 'kailis-sunset.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'The Loop Mania',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 20,
                'description'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'image'                 => 'the-loop-mania.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Heineken',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 20,
                'description'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'image'                 => 'heineken.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ]
        ];

        $entreeItems = [
            [
                'name'                  => 'Rockmelon Brucheta',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'rockmelon-brucheta.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Antipasto Platter',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'antipasto-platter.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Candied tomatoes on basil leaves',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'candied-tomatoes.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ]
        ];

        $mainsItems = [
            [
                'name'                  => 'Portuguese Chicken',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'portuguese-chicken.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Lamington',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'lamington.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Meat Pie',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'meat-pie.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Spaghetti Bolongnese',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'spaghetti.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
        ];

        $sidesItems = [
            [
                'name'                  => 'Broccolini salad with roasted garlic dressing',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'broccolini-salad.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Cos salad with buttermilk dressing and jalapeno crumb',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'cos-salad.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Chorizo and sweet potato salad',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'chorizo-sweet-potato.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Green bean, fig and feta salad',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'green-bean.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ]
        ];

        $chefItems = [
            [
                'name'                  => 'Spring green risotto',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'spring-green-risotto.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Roasted beetroot tart with whipped ricotta',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'beetroot-tart.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ],
            [
                'name'                  => 'Egg, bacon and tomato tart',
                'type'                  => RestaurantItem::ITEM,
                'is_variable'           => 0,
                'price'                 => 35,
                'image'                 => 'egg-becon-tomato-tart.jpg',
                'ingredients'           => '45 ml Johnnie Walker Black Label 180 ml club soda ',
                'country_of_origin'     => 'Latin America',
                'year_of_production'    => '1934',
                'type_of_drink'         => 'Spirit'
            ]
        ];

        if( $restaurants->count() )
        {
            foreach( $restaurants as $restaurant )
            {
                // get all the categories
                $categories = $restaurant->categories;

                if( $restaurant->id == 1 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 2 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 3 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 4 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 5 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 6 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 7 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 8 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 9 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 10 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 11 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 12 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 13 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 14 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if( $restaurant->id == 15 )
                {
                    if( !empty( $categories ) )
                    {
                        foreach( $categories as $category )
                        {
                            if( $category->name === 'Drinks' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Spirits' )
                                        {
                                            foreach( $spiritItems as $spirit )
                                            {
                                                $spirit['restaurant_id'] = $restaurant->id;
                                                $image = $spirit['image'];
                                                unset($spirit['image']);
                                                $spirit['is_featured'] = 1;
                                                $spirit['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($spirit);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Cocktails' )
                                        {
                                            foreach( $cocktailItems as $cocktail )
                                            {
                                                $cocktail['restaurant_id'] = $restaurant->id;
                                                $image = $cocktail['image'];
                                                unset($cocktail['image']);
                                                $cocktail['is_featured'] = 1;
                                                $cocktail['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($cocktail);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Beers' )
                                        {
                                            foreach( $beersItems as $beer )
                                            {
                                                $beer['restaurant_id'] = $restaurant->id;
                                                $image = $beer['image'];
                                                unset($beer['image']);
                                                $beer['is_featured'] = 1;
                                                $beer['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($beer);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 35
                                                    ],
                                                    [
                                                        'name' => 'Jug',
                                                        'price'=> 50
                                                    ],
                                                    [
                                                        'name' => 'Pint',
                                                        'price'=> 75
                                                    ]
                                                ];

                                                foreach( $variationsArr as $variation )
                                                {
                                                    $newItem->variations()->create($variation);
                                                }

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Wines' )
                                        {
                                            foreach( $wineItems as $wine )
                                            {
                                                $wine['restaurant_id'] = $restaurant->id;
                                                $image = $wine['image'];
                                                unset($wine['image']);
                                                $wine['is_featured'] = 1;
                                                $wine['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($wine);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Single Shot',
                                                        'price'=> 10
                                                    ],
                                                    [
                                                        'name' => 'Double Shot',
                                                        'price'=> 20
                                                    ],
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Champagne' )
                                        {
                                            foreach( $champagneItems as $champagne )
                                            {
                                                $champagne['restaurant_id'] = $restaurant->id;
                                                $image = $champagne['image'];
                                                unset($champagne['image']);
                                                $champagne['is_featured'] = 1;
                                                $champagne['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($champagne);

                                                // variations
                                                $variationsArr = [
                                                    [
                                                        'name' => 'Glass',
                                                        'price'=> 30
                                                    ],
                                                    [
                                                        'name' => 'Bottle',
                                                        'price'=> 50
                                                    ]
                                                ];

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            if( $category->name === 'Food' )
                            {
                                $subcategories = $category->children;
                                if( !empty( $subcategories->count() ) )
                                {
                                    foreach( $subcategories as $subcategory )
                                    {
                                        if( $subcategory->name == 'Entree' )
                                        {
                                            foreach( $entreeItems as $entree )
                                            {
                                                $entree['restaurant_id'] = $restaurant->id;
                                                $image = $entree['image'];
                                                unset($entree['image']);
                                                $entree['is_featured'] = 1;
                                                $entree['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($entree);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Mains' )
                                        {
                                            foreach( $mainsItems as $main )
                                            {
                                                $main['restaurant_id'] = $restaurant->id;
                                                $image = $main['image'];
                                                unset($main['image']);
                                                $main['is_featured'] = 1;
                                                $main['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($main);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Chefs Special' )
                                        {
                                            foreach( $chefItems as $chef )
                                            {
                                                $chef['restaurant_id'] = $restaurant->id;
                                                $image = $chef['image'];
                                                unset($chef['image']);
                                                $chef['is_featured'] = 1;
                                                $chef['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($chef);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }

                                        if( $subcategory->name == 'Sides' )
                                        {
                                            foreach( $sidesItems as $side )
                                            {
                                                $side['restaurant_id'] = $restaurant->id;
                                                $image = $side['image'];
                                                unset($side['image']);
                                                $side['is_featured'] = 1;
                                                $side['category_id'] = $subcategory->id;
                                                $newItem = RestaurantItem::create($side);

                                                // attachment
                                                $newItem->attachment()->create([
                                                    'original_name' => $image,
                                                    'stored_name'   => $image
                                                ]);
                                            }
                                        }
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
