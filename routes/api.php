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
    });

    Route::group(['prefix' => 'auth', 'middleware' => 'auth:api'], function ()
    {
        // {api/v1/auth/logout}
        Route::post('logout', 'AuthController@logout')->name('auth.logout');

        // vanue list{api/v1/restaurants}
        Route::post('restaurants', 'RestaurantController@index')->name('restaurant.index');
    });
});
