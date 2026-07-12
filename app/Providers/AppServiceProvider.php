<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Remove_Frome_remaining;
use App\Observers\AuthorObserver;
use App\Observers\BookObserver;
use App\Observers\CategoryObserver;
use App\Observers\OpreationOnStockObserver;
use App\Observers\PaymentObserver;
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
    Book::observe(BookObserver::class);
    Author::observe(AuthorObserver::class);
    Category::observe(CategoryObserver::class);
    Payment::observe(PaymentObserver::class);
}
}
