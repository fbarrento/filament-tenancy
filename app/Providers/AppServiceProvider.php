<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TenantForge\Support\Livewire\DatabaseNotifications;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(
            \Filament\Livewire\DatabaseNotifications::class,
            DatabaseNotifications::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
