<?php

namespace App\Providers;

use App\Services\Access;
use App\Services\Common;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('access', function ($app) {
            return new Access();
        });

        $this->app->singleton('common', function ($app) {
            return new Common();
        });
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
    }
}
