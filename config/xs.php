<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Membership
    |--------------------------------------------------------------------------
    |
    | Membership is related to user points. Whenever user place order and complete
    | payment then whichever order amount convert into points and update the user points.
    | There are four types of memberships. 1. Bronze 2. Silver 3. Gold 4. Platinum
    | Every Membership depends on the points earned by the customer by every quarter.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Bronze Membership
    |--------------------------------------------------------------------------
    |
    | Bronze membership contain the bronze color in the app.
    |
    */
    'bronze'                => [0, 100],
    'bronze_membership'     => 'BRONZE',
    'bronze_level'          => 1,

    /*
    |--------------------------------------------------------------------------
    | Silver Membership
    |--------------------------------------------------------------------------
    |
    | Silver membership contain the silver color in the app.
    |
    */
    'silver'                => [101, 200],
    'silver_membership'     => 'SILVER',
    'silver_level'          => 2,

    /*
    |--------------------------------------------------------------------------
    | Gold Membership
    |--------------------------------------------------------------------------
    |
    | Gold membership contain the golden color in the app.
    |
    */
    'gold'                  => [201, 300],
    'gold_membership'       => 'GOLD',
    'gold_level'            => 3,

    /*
    |--------------------------------------------------------------------------
    | Platinum Membership [>300 is Platinum membership]
    |--------------------------------------------------------------------------
    |
    | Platinum membership contain the platinum color in the app.
    |
    */
    'platinum'              => [300],
    'platinum_membership'   => 'PLATINUM',
    'platinum_level'        => 4
];
