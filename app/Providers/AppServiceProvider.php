<?php

namespace App\Providers;

use App\Services\HistoryClass;
use App\Services\WalmartClass;
use App\Services\NotificationsClass;
use App\Services\UpdateContentClass;
use Illuminate\Support\ServiceProvider;
use App\Services\CronJobUpdateDataClass;
use App\Services\Contractors\WalmartInterfase;
use App\Services\Contractors\HistoryInterface;
use App\Services\Contractors\UpdateContentInterface;
use App\Services\Contractors\NotificationsInterface;
use App\Services\Contractors\CronJobUpdateDataInterface;

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
        $this->app->bind(HistoryInterface::class, HistoryClass::class);
        $this->app->bind(WalmartInterfase::class, WalmartClass::class);
        $this->app->bind(UpdateContentInterface::class, UpdateContentClass::class);
        $this->app->bind(NotificationsInterface::class, NotificationsClass::class);
        $this->app->bind(CronJobUpdateDataInterface::class, CronJobUpdateDataClass::class);
    }
}
