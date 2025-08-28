<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }

    Carbon::macro('justDiffForHumans', function () {
         $seconds = $this->diffInSeconds();

        if ($seconds < 60) {
            return 'just now';
        }

        return $this->diffForHumans();
    });
    }
}
