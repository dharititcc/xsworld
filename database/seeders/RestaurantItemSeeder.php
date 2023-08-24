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
                'name'          => 'Veuve Brut',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'veuve-brut.jpg'
            ],
            [
                'name'          => 'Pommery Brut Royal',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 85,
                'image'         => 'pommery-brut-royal.jpg'
            ]
        ];

        $wineItems = [
            [
                'name'          => 'Pirate Life',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 85,
                'image'         => 'pirate-life.jpg'
            ],
            [
                'name'          => 'Rasteau',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 16,
                'image'         => 'rasteau.jpg'
            ]
        ];

        $spiritItems = [
            [
                'name'          => 'Rum Range',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 85,
                'image'         => 'rum-range.jpg'
            ],
            [
                'name'          => 'Whisky Range',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 85,
                'image'         => 'whisky-range.jpg'
            ]
        ];

        $cocktailItems = [
            [
                'name'          => 'Kailis Sunset',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 22,
                'image'         => 'kailis-sunset.jpg'
            ],
            [
                'name'          => 'The Loop Mania',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 20,
                'image'         => 'the-loop-mania.jpg'
            ]
        ];

        $beersItems = [
            [
                'name'          => 'Buckle Bunny Sour',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 0,
                'price'         => 22,
                'description'   => '',
                'image'         => 'buckle-bunny-sour.jpg'
            ],
            [
                'name'          => '805 Draft',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 0,
                'price'         => 22,
                'description'   => '',
                'image'         => '805-draft.jpg'
            ],
            [
                'name'          => 'Kailis Sunset',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 0,
                'price'         => 22,
                'description'   => '',
                'image'         => 'kailis-sunset.jpg'
            ],
            [
                'name'          => 'The Loop Mania',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 0,
                'price'         => 20,
                'description'   => '',
                'image'         => 'the-loop-mania.jpg'
            ],
            [
                'name'          => 'Heineken',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 0,
                'price'         => 20,
                'description'   => '',
                'image'         => 'heineken.jpg'
            ]
        ];

        $entreeItems = [
            [
                'name'          => 'Rockmelon Brucheta',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'rockmelon-brucheta.jpg'
            ],
            [
                'name'          => 'Antipasto Platter',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'antipasto-platter.jpg'
            ],
            [
                'name'          => 'Candied tomatoes on basil leaves',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'candied-tomatoes.jpg'
            ]
        ];

        $mainsItems = [
            [
                'name'          => 'Portuguese Chicken',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'portuguese-chicken.jpg'
            ],
            [
                'name'          => 'Lamington',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'lamington.jpg'
            ],
            [
                'name'          => 'Meat Pie',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'meat-pie.jpg'
            ],
            [
                'name'          => 'Spaghetti Bolongnese',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'spaghetti.jpg'
            ],
        ];

        $sidesItems = [
            [
                'name'          => 'Broccolini salad with roasted garlic dressing',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'broccolini-salad.jpg'
            ],
            [
                'name'          => 'Cos salad with buttermilk dressing and jalapeno crumb',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'cos-salad.jpg'
            ],
            [
                'name'          => 'Chorizo and sweet potato salad',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'chorizo-sweet-potato.jpg'
            ],
            [
                'name'          => 'Green bean, fig and feta salad',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'green-bean.jpg'
            ]
        ];

        $chefItems = [
            [
                'name'          => 'Spring green risotto',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'spring-green-risotto.jpg'
            ],
            [
                'name'          => 'Roasted beetroot tart with whipped ricotta',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'beetroot-tart.jpg'
            ],
            [
                'name'          => 'Egg, bacon and tomato tart',
                'type'          => RestaurantItem::ITEM,
                'is_variable'   => 1,
                'price'         => 35,
                'image'         => 'egg-becon-tomato-tart.jpg'
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
