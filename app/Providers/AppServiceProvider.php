<?php

namespace App\Providers;

use App\Console\Commands\ExpireStaleRequests;
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
        View::composer('layouts.navigation', function ($view): void {
            $view->with('unreadNotificationCount', auth()->check()
                ? auth()->user()->notifications()->where('is_read', false)->count()
                : 0);
        });
    }
}
