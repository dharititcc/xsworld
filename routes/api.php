<?php

use App\Http\Controllers\Api\V1\Waiter\AuthController as WaiterAuthController;
use App\Http\Controllers\Api\V1\Waiter\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Login api ///
Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
    Route::group(['prefix' => 'auth', 'middleware' => ['guest']], function ()
    {
        // {api/v1/auth/login}
        Route::post('login', 'AuthController@postLogin')->name('auth.login');
        // {api/v1/auth/register}
        Route::post('register', 'AuthController@postRegister')->name('auth.register');
        // {api/v1/auth/social-register}
        Route::post('social-register', 'AuthController@socialRegister')->name('auth.socialregister');
        // {api/v1/auth/send-otp}
        Route::post('send-otp', 'AuthController@sendOtp')->name('auth.sendOtp');
        // {api/v1/auth/resend-link}
        Route::post('resend-link', 'AuthController@resendLink')->name('auth.resendLink');
        // {api/v1/users/password/reset}
        Route::patch('password/reset', 'AuthController@resetPassword')->name('user.resetPassword');
    });

    Route::group(['prefix' => 'auth', 'middleware' => 'auth:api'], function ()
    {   // {api/v1/auth/verify-otp}
        Route::post('verify-otp', 'AuthController@VerifyOtp')->name('auth.VerifyOtp');
        // {api/v1/auth/logout}
        Route::post('logout', 'AuthController@logout')->name('auth.logout');
    });

    Route::group(['middleware' => 'auth:api'], function ()
    {
        // vanue list{api/v1/restaurants}
        Route::post('/restaurants', 'RestaurantController@index')->name('restaurant.index');
        // event list{api/v1/restaurants}
        Route::post('/events', 'RestaurantController@events')->name('restaurant.events');
        // restaurant featured list{api/v1/restaurants/featured}
        Route::post('/restaurants/featured', 'RestaurantController@featured')->name('restaurant.featured');
        // restaurant item type list{api/v1/restaurants/sub-categories}
        Route::post('/restaurants/sub-categories', 'RestaurantController@subCategories')->name('restaurant.itemtype');
        // restaurant item type list{api/v1/restaurants/item-search-by-name}
        Route::post('/restaurants/item-search-by-name', 'RestaurantController@itemSearchByName')->name('restaurant.item-search-by-name');
        // vanue item list{api/v1/restaurants/items}
        Route::post('/restaurants/items', 'RestaurantItemController@index')->name('restaurant.items.index');
        // {api/v1/users/addtocart}
        Route::post('/addtocart', 'OrderController@addToCart')->name('addtocart');
        // {api/v1/users/viewcart}
        Route::post('/viewcart', 'OrderController@viewCart')->name('viewcart');
        Route::get('/current-order', 'OrderController@currentOrder')->name('order.current-order');
        Route::get('/show/{order}', 'OrderController@show')->name('order.show');
        // {api/v1/users/orderhistory}
        Route::post('/orderhistory', 'OrderController@orderHistory')->name('orderhistory');
        // {api/v1/users/cart}
        Route::get('/cart', 'OrderController@cartCount')->name('cart');
        // {api/v1/cart/update}
        Route::post('/updatecart', 'OrderController@updateCart')->name('order.update');
        // {api/v1/deleteitem}
        Route::post('/deleteitem', 'OrderController@deleteItem')->name('deleteitem');
        // {api/v1/deleteorder}
        Route::post('/deletecart', 'OrderController@deleteCart')->name('deletecart');
        // {api/v1/placeorder}
        Route::post('/placeorder', 'OrderController@placeOrder')->name('placeorder');
        // {api/v1/orderstatusupdate}
        Route::post('/orderstatusupdate', 'OrderController@orderStatusUpdate')->name('orderStatusUpdate');
        // {api/v1/order-review}
        Route::post('/order-review', 'OrderController@orderReview')->name('orderReview');
    });

    Route::group(['prefix' => 'users','middleware' => 'auth:api'], function ()
    {
        // {api/v1/users/get-profile}
        Route::post('update-profile', 'UserController@updateProfile')->name('user.update-profile');
        // {api/v1/users/change-password}
        Route::patch('change-password', 'UserController@changePassword')->name('user.change-password');
        // {api/v1/users/get-profile}
        Route::get('get-profile', 'UserController@me')->name('get-profile');
        // {api/v1/users/favourite}
        Route::post('favourite', 'UserController@favorite')->name('user.favorite');
        // {api/v1/users/fetchcard}
        Route::get('fetchcard', 'UserController@fetchCard')->name('user.fetchcard');
        // {api/v1/users/fetchcard}
        Route::post('delectcard', 'UserController@delectcard')->name('user.delectcard');
        // {api/v1/users/attach-card}
        Route::post('attach-card', 'UserController@attachCard')->name('user.attach-card');
        // {api/v1/users/mark-default-card}
        Route::post('mark-default-card', 'UserController@markdefaultcard')->name('user.mark-default-card');
    });

    Route::group(['prefix' => 'countries'], function ()
    {
        // {api/v1/countries/}
        Route::get('get-countries', 'CountryController@index')->name('countries.index');
    });

    Route::group(['namespace' => 'Bartender', 'prefix' => 'bartender'], function()
    {
        Route::group(['middleware' => ['guest']], function()
        {
            // {api/v1/bartender/login}
            Route::post('login', 'AuthController@postLogin')->name('bartender.login');
        });

        Route::group(['middleware' => 'auth:api'], function ()
        {
            // {api/v1/bartender/logout}
            Route::post('logout', 'AuthController@logout')->name('bartender.logout');

            // orders
            // {api/v1/orderupdate}
            Route::post('/orderupdate', 'BarController@orderUpdate')->name('barOrderUpdate');
            // {api/v1/barorderhistory}
            Route::get('/barorderhistory', 'BarController@barOrderHistory')->name('barOrderHistory');
            // {api/v1/completedorderhistory}
            Route::post('/completedorderhistory', 'BarController@completedorderhistory')->name('completedorderhistory');
            Route::get('/show/{order}', 'BarController@show')->name('bar.show');
            Route::post('/gostatus', 'BarController@gostatus')->name('barstatus');
        });
    });

    Route::group(['namespace' => 'Kitchen', 'prefix' => 'kitchen'], function()
    {
        Route::group(['middleware' => ['guest']], function()
        {
            // {api/v1/kitchen/login}
            Route::post('login', 'AuthController@postLogin')->name('kitchen.login');

            // {api/v1/kitchen/password/reset}
            Route::patch('password/reset', 'AuthController@resetPassword')->name('kitchen.resetPassword');

            // {api/v1/kitchen/orderList}
        });

        Route::group(['middleware' => 'auth:api'], function ()
        {
            // {api/v1/kitchen/logout}
            Route::post('logout', 'AuthController@logout')->name('kitchen.logout');

            Route::get('order/list','OrderController@orderList')->name('kitchen.order.list');
            Route::get('order-history','OrderController@orderHistory')->name('kitchen.order.history');
            Route::post('order-update-status','OrderController@updateOrderStauts')->name('kitchen.order.update.status');
            Route::post('order-show','OrderController@orderDetail')->name('kitchen.order.show');
            Route::post('gostatus','OrderController@gostatus')->name('kitchen.gostatus');

            // // orders
            // // {api/v1/orderupdate}
            // Route::post('/orderupdate', 'BarController@orderUpdate')->name('barOrderUpdate');
            // // {api/v1/barorderhistory}
            // Route::get('/barorderhistory', 'BarController@barOrderHistory')->name('barOrderHistory');
            // // {api/v1/completedorderhistory}
            // Route::post('/completedorderhistory', 'BarController@completedorderhistory')->name('completedorderhistory');
            // Route::get('/show/{order}', 'BarController@show')->name('bar.show');
            // Route::post('/gostatus', 'BarController@gostatus')->name('barstatus');
        });
    });


    Route::group(['namespace' => 'Waiter', 'prefix' => 'waiter'], function(){
        Route::group(['middleware' => ['guest']], function() {
             // {api/v1/waiter/login}
            Route::post('login', [WaiterAuthController::class,'postLogin'])->name('waiter.login');
        });

        Route::group(['middleware' => 'auth:api'], function() {
            // {api/v1/waiter/logout}
            Route::post('logout', [WaiterAuthController::class, 'logout'] )->name('kitchen.logout');
            Route::get('order-tbl-list',[HomeController::class,'activeTable'])->name('waiter.active.tbl');
            Route::post('gostatus',[HomeController::class,'gostatus'])->name('kitchen.gostatus');
        });
    });
});
