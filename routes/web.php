<?php

use App\Http\Controllers\AccountManager\Bar\BarPickZoneController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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
Route::get('seed', function()
{
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    // Artisan::call('migrate');
    // Artisan::call('passport:install');

    dd('migrate:rollback');
});
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'restaurants', 'as' => 'restaurants.', 'middleware' => ['auth']], function ()
{
    Route::group(['namespace' => 'Categories'], function()
    {
        Route::resource('categories', 'CategoryController');
        Route::post('categories/multidelete', 'CategoryController@deleteCategories')->name('delete/categories');
    });
    Route::group(['namespace' => 'Drinks'], function()
    {
        Route::resource('drinks', 'DrinkController');
    });
    Route::group(['namespace' => 'Foods'], function()
    {
        Route::resource('foods', 'FoodController');
    });
    Route::group(['namespace' => 'Mixers'], function()
    {
        Route::resource('mixers', 'MixerController');
    });
    Route::group(['namespace' => 'Addons'], function()
    {
        Route::resource('addons', 'AddonsController');
    });

    Route::group(['namespace' => 'Pickup'], function()
    {
        Route::resource('pickup', 'PickupZoneController');
    });

    Route::group(['namespace' => 'Table'], function()
    {
        Route::resource('table', 'RestaurantTableController');
    });

    Route::group(['namespace' => 'AccountManager'], function()
    {
        Route::resource('accountmanager', 'AccountManagerController');
        Route::group(['namespace' => 'Waiter'], function()
        {
            Route::resource('waiter', 'WaiterController');
            Route::get('waiter/email','WaiterController@generateRandomString');
        });

        Route::group(['namespace' => 'Bar'], function()
        {
            Route::resource('barpickzone', 'BarPickZoneController');
        });

        Route::group(['namespace' => 'Kitchen'], function()
        {
            Route::resource('kitchen', 'KitchenController');
        });
    });

});

Route::get('test', fn () => phpinfo());
Route::middleware(['guest'])->group(function()
{
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function()
    {
        // verify email
        Route::get('verify/{token}', 'XSWorldVerificationController@verify')->name('verify-email');

        Route::get('verification-success/{token}', 'XSWorldVerificationController@verificationSuccess')->name('verification-success');
    });
});


