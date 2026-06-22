<?php

namespace App\Providers;
use App\Models\Remove_Frome_remaining;
use App\Observers\OpreationOnStockObserver;
use Illuminate\Support\ServiceProvider;

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
    Remove_Frome_remaining::observe(OpreationOnStockObserver::class);
}
}
