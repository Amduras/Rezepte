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
        // Erzwingt HTTPS für alle asset() und url() Helfer, wenn wir über DuckDNS laufen
        //        if (str_contains(request()->getHost(), 'duckdns.org')) {
        //          URL::forceScheme('https');
        //     }
        Paginator::useTailwind();
    }
}
