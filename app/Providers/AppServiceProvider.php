<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        //
        // Laravel'e tüm sayfalama linklerinde Bootstrap 5 CSS sınıflarını kullanmasını söyler.
        Paginator::useBootstrapFive();
    }
}
