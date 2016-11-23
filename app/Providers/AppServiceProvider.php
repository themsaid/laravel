<?php

namespace App\Providers;

use App\Localizer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function booted()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function () {
            (new Localizer)->localizeRoutes(app('router'));
        });
    }
}
