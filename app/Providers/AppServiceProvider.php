<?php

namespace App\Providers;

use App\Services\Walmart;
use App\Services\NotificationsClass;
use App\Services\Contractors\WalmartInterfase;
use App\Services\Contractors\NotificationsInterfase;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
        // Register repositories bindings here
        $this->app->bind(WalmartInterfase::class, Walmart::class);
        $this->app->bind(NotificationsInterfase::class, NotificationsClass::class);
    }
}
