<?php

declare(strict_types=1);

namespace TenantForge\Support;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TenantForge\Support\Console\Commands\PluginsMigrate;
use TenantForge\Support\Livewire\DatabaseNotifications;

final class SupportServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {

        $this->registerMigrations();

        $this->commands([
            PluginsMigrate::class,
        ]);

        $this->configureLivewireComponents();
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function configureLivewireComponents(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }
        Livewire::component('database-notifications', DatabaseNotifications::class);
    }
}
