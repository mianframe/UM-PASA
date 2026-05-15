<?php

namespace App\Providers;

use App\Console\Commands\ExpireStaleRequests;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExpireStaleRequests::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            $appUrl = config('app.url');

            if (is_string($appUrl) && str_starts_with($appUrl, 'https://') && ! str_contains($appUrl, 'your-domain.example')) {
                URL::forceRootUrl($appUrl);
            }
        }

        View::composer('layouts.navigation', function ($view): void {
            $view->with('unreadNotificationCount', auth()->check()
                ? auth()->user()->notifications()->where('is_read', false)->count()
                : 0);
        });
    }
}
