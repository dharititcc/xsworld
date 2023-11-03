<?php

use App\Http\Controllers\AccountManager\Bar\BarPickZoneController;
use App\Http\Controllers\Drinks\DrinkController;
use App\Http\Controllers\Table\RestaurantTableController;
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
Route::get('referral/user/ABC', [App\Http\Controllers\ReferralController::class, 'code'])->name('referral-code');
Route::get('apple-app-site-association', [App\Http\Controllers\ReferralController::class, 'iphone'])->name('referral-code-iphone');
Route::get('.well-known/apple-app-site-association', [App\Http\Controllers\ReferralController::class, 'iphone'])->name('referral-code-iphone');

Route::group(['prefix' => 'restaurants', 'as' => 'restaurants.', 'middleware' => ['auth']], function ()
{
    Route::group(['namespace' => 'Categories'], function()
    {
        Route::resource('categories', 'CategoryController');
        Route::post('categories/multidelete', 'CategoryController@deleteCategories')->name('delete/categories');
        Route::post('categoryName', 'CategoryController@categoryName')->name('categoryName');
    });
    Route::group(['namespace' => 'Drinks'], function()
    {
        Route::resource('drinks', 'DrinkController');
        Route::post('favorite-status-update',[DrinkController::class,'favoriteStatusUpdate'])->name('favoriteStatusUpdate');
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
        Route::get('status/update','RestaurantTableController@statusUpdate')->name('table-status');
        // Route::get('exportQrCode','RestaurantTableController@exportQrCode')->name('exportQrCode');
        Route::post('export_pdf','RestaurantTableController@export_pdf')->name('table-export_pdf');
        Route::delete('table/delete','RestaurantTableController@destroy')->name('table-destroy');
    });

    Route::group(['namespace' => 'AccountManager'], function()
    {
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

    Route::group(['namespace' => 'Venue'], function()
    {
        Route::resource('venue', 'VenueController');
        Route::post('imageupload','VenueController@imageUpload')->name('res-image-upload');
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

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function()
    {
        Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function()
        {
            Route::get('login', 'AuthController@showLoginForm')->name('login');
            Route::post('login', 'AuthController@login')->name('post-login');
        });
    });
});

Route::middleware(['admin'])->group(function()
{
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function()
    {
        Route::get('/home', 'HomeController@index')->name('home');

        Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function()
        {
            Route::post('/logout', 'LogoutController')->name('logout');
        });

        Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function()
        {
            Route::resource('/customer', 'CustomerController');
            Route::post('/get', 'CustomerTableController')->name('customer.table');
        });

        Route::group(['namespace' => 'Event', 'prefix' => 'event'], function()
        {
            Route::resource('/event', 'EventController');
            Route::post('/event/get', 'EventTableController')->name('event.table');
        });

        Route::group(['namespace' => 'Restaurant', 'prefix' => 'restaurant'], function()
        {
            Route::resource('/restaurant', 'RestaurantController');
            Route::post('/restaurant/get', 'RestaurantTableController')->name('restaurant.table');
        });
    });
});