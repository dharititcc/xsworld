<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'restaurants', 'as' => 'restaurants.', 'middleware' => ['auth']], function ()
{
    Route::group(['namespace' => 'Categories'], function()
    {
        Route::resource('categories', 'CategoryController');
    });
    Route::group(['namespace' => 'Drinks'], function()
    {
        Route::resource('drinks', 'DrinkController');
    });
    Route::group(['namespace' => 'Foods'], function()
    {
        Route::resource('foods', 'FoodController');
    });
});


