<?php

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
        // {api/v1/users/password/reset}
        Route::patch('password/reset', 'AuthController@resetPassword')->name('user.resetPassword');
    });

    Route::group(['prefix' => 'auth', 'middleware' => 'auth:api'], function ()
    {
        // {api/v1/auth/logout}
        Route::post('logout', 'AuthController@logout')->name('auth.logout');
    });

    Route::group(['middleware' => 'auth:api'], function ()
    {
        // vanue list{api/v1/restaurants}
        Route::post('/restaurants', 'RestaurantController@index')->name('restaurant.index');
        // restaurant featured list{api/v1/restaurants/featured}
        Route::post('/restaurants/featured', 'RestaurantController@featured')->name('restaurant.featured');
        // vanue item list{api/v1/restaurants/items}
        Route::post('/restaurants/items', 'RestaurantItemController@index')->name('restaurant.items.index');
        // {api/v1/auth/get-profile}
        Route::get('get-profile', 'AuthController@me')->name('get-profile');
    });

    Route::group(['prefix' => 'users','middleware' => 'auth:api'], function ()
    {
        // {api/v1/auth/get-profile}
        Route::post('update-profile', 'UserController@updateProfile')->name('user.update-profile');
        // {api/v1/users/change-password}
        Route::patch('change-password', 'UserController@changePassword')->name('user.change-password');
        // {api/v1/users/favourite}
        Route::post('favourite', 'UserFavouriteItemsController@favorite')->name('user.favorite');
    });
});
