<?php

namespace App\Providers;

use App\Livewire\DatabaseNotifications;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
    public function boot(): void
    {
        if (class_exists(Livewire::class)) {
            Livewire::component('database-notifications', DatabaseNotifications::class);
        }

    }
}
