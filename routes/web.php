<?php

use App\Http\Controllers\AccountManager\Bar\BarPickZoneController;
use App\Http\Controllers\Admin\Restaurant\RestaurantController;
use App\Http\Controllers\Drinks\DrinkController;
use App\Http\Controllers\Foods\FoodController;
use App\Http\Controllers\LeaveImpersonateController;
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
// test pdf
Route::get('/pdf', [App\Http\Controllers\Api\V1\OrderController::class, 'generatePdf']);
Route::impersonate();

Route::get('leave-imporsonate', 'LeaveImpersonateController@leave')->name('leave');

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

// sample qr testing
Route::get('qrcode-with-color', function () {
    return \QrCode::size(500)
                    ->format('png')
                    // ->backgroundColor(255,55,0)
                    ->color(255,255,255)->backgroundColor(0,0,0)->margin(0)
                    ->generate('A simple example of QR code', public_path('images/qrcode.png'));
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/analytics', [App\Http\Controllers\HomeController::class, 'analytics'])->name('analytics');
Route::post('/filter-analytics', [App\Http\Controllers\HomeController::class, 'filterAnalytics'])->name('filter.analytics');
Route::get('referral/user/ABC', [App\Http\Controllers\ReferralController::class, 'code'])->name('referral-code');
// Route::get('apple-app-site-association', [App\Http\Controllers\ReferralController::class, 'iphone'])->name('referral-code-iphone');

Route::get('/apple-app-site-association', function () {
    $json = file_get_contents(base_path('.well-known/apple-app-site-association'));
    return response($json, 200)
        ->header('Content-Type', 'application/json');
});

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
        Route::get('sample-file-drink',[DrinkController::class,'SampleFileDrink'])->name('SampleFileDrink');
        Route::post('upload-drink-data',[DrinkController::class,'uploadDrinkData'])->name('uploadDrinkData');
    });
    Route::group(['namespace' => 'Foods'], function()
    {
        Route::resource('foods', 'FoodController');
        Route::post('upload-food-data',[FoodController::class,'uploadFoodData'])->name('uploadFoodData');
        Route::get('sample-file-food',[FoodController::class,'SampleFileFood'])->name('SampleFileFood');
    });
    Route::group(['namespace' => 'Orders'], function()
    {
        Route::resource('orders', 'OrderController');
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
        Route::post('venue-edit','VenueController@venueUpdate')->name('venue-edit');
    });

});

Route::get('test', fn () => phpinfo());
Route::group(['namespace' => 'Auth', 'prefix' => 'auths', 'as' => 'auths.'], function()
{
    Route::get('password-change', 'XSWorldVerificationController@passwordChange')->name('password-change')->withoutMiddleware(['web']);
});
Route::middleware(['guest'])->group(function()
{
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function()
    {
        // verify email
        Route::get('verify/{token}', 'XSWorldVerificationController@verify')->name('verify-email');
        Route::get('token-expiry', 'XSWorldVerificationController@tokenExpiry')->name('token-expiry');

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
        Route::get('/', 'HomeController@index')->name('admin-dashboard');

        Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function()
        {
            Route::post('/logout', 'LogoutController')->name('logout');
        });

        Route::get('location', function() {
            // Google Maps API Key 
            $GOOGLE_API_KEY = 'AIzaSyBzaUUaqbCwcmb_TMSSnEQ5q0Qr5Sib7i4'; 
            
            // Address from which the latitude and longitude will be retrieved 
            $address = 'B-101-104 sakar 7, near patang hotel, Nehru bridge, corner, Ashram Rd'; 
            
            // Formatted address 
            $formatted_address = str_replace(' ', '+', $address); 
            
            // Get geo data from Google Maps API by address 
            $geocodeFromAddr = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$formatted_address}&key={$GOOGLE_API_KEY}"); 
            
            // Decode JSON data returned by API 
            $apiResponse = json_decode($geocodeFromAddr); 
            // Retrieve latitude and longitude from API data 
            $latitude  = $apiResponse->results[0]->geometry->location->lat;  
            $longitude = $apiResponse->results[0]->geometry->location->lng;
            dd("latitude = " . $latitude . " longitude = " . $longitude);
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
            Route::delete('restaurant/{id}', 'RestaurantController@destroy')->name('admin.restaurant.destroy');
        });
    });
});