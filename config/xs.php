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
    'bronze'    => 100,
    'bronze_membership' => 'BRONZE',

    /*
    |--------------------------------------------------------------------------
    | Silver Membership
    |--------------------------------------------------------------------------
    |
    | Silver membership contain the silver color in the app.
    |
    */
    'silver'    => 200,
    'silver_membership' => 'SILVER',

    /*
    |--------------------------------------------------------------------------
    | Gold Membership
    |--------------------------------------------------------------------------
    |
    | Gold membership contain the golden color in the app.
    |
    */
    'gold'      => 300,
    'gold_membership' => 'GOLD',

    /*
    |--------------------------------------------------------------------------
    | Platinum Membership
    |--------------------------------------------------------------------------
    |
    | Platinum membership contain the platinum color in the app.
    |
    */
    'platinum'  => 400,
    'platinum_membership' => 'PLATINUM',
];
