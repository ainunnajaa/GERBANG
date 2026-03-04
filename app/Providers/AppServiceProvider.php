<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\URL; // https://oiruu-157-10-8-142.a.free.pinggy.link/
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
   // URL::forceScheme('https'); // https://oiruu-157-10-8-142.a.free.pinggy.link/
    }
}